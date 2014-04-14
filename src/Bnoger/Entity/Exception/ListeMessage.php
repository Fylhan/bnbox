<?php
require_once('Message.class.php');

class ListeMessage {
	/**
	 * Liste des messages
	*/
	protected $messages;
	
	public function ListeMessage()
	{
		return $this->_construct();
	}
	
	public function _construct()
	{
		$this->messages = array();
	}
	
	/**
	 * Affiche un message d'information ou d'erreur
	 */
	public function display($position='', $return=0)
	{
		$globalRes = NULL;
		foreach($this->messages AS $msg) {
			if (!$msg->isVide()) {
				$res = '<div class="msg'.($msg->getType() != NULL ? ' '.$msg->getType() : ' neutre').'">
					'.$msg->getData().'
				</div>';
				if ($return == 0) {
					echo $res;
					// Si c'est un message bloquant, on arrête là
					if ($msg->isBloquant()) {
						exit();
					}
				} else {
					$globalRes .= $res;
					// Si c'est un message bloquant, on ne traite pas les autres messages
					if ($msg->isBloquant()) {
						break;
					}
				}			
			}
		}
		if (NULL != $globalRes) {
			return $globalRes;
		}
	}
		
	/**
	 * Redirige et affiche un message d'information ou d'erreur
	 * @param $msgInfo Message info contenant l'url de redirection et l'id du message à afficher ensuite
	*/
	public function rediriger()
	{
		foreach($this->messages AS $msg) {
			if ($msg->isRedirect()) {
				if(!$msg->isVide()) {
					header('Location: '.$msg->getData());
				}
				else {
					header('Location: ?msg=Erreur lors de la redirection');
				}
				break;
			}
		}
		if (isset($_GET['data']) && NULL != $_GET['data']  && '' != $_GET['data']) {
			$this->addNonBloquant(htmlspecialchars_decode(base64_decode($_GET['data'])), base64_decode(@$_GET['position']), @$_GET['type']);
		}
	}
	
	/* --- Mutator --- */
	public function addNonBloquant($data='', $position='', $type='')
	{
		$this->addMessage(new Message($data, $position, $type, 0));
	}
	public function addBloquant($data='', $position='', $type='')
	{
		$this->addMessage(new Message($data, $position, $type, 1));
	}
	public function addRedirect($url, $data='', $position='', $type='')
	{
		$this->addMessage(new Message($url.(preg_match('!\?!', $url) ? '&' : '?').'data='.base64_encode(htmlspecialchars($data)).'&position='.base64_encode($position).'&type='.$type, $position, $type, 2));
	}
	public function addMessage($message)
	{
		$this->logger($message);
		$this->messages[] = $message;
	}
	
	public function setNonBloquant($data='', $position='', $type='')
	{
		$this->setMessage(new Message($data, $position, $type, 0));
	}
	public function setBloquant($data='', $position='', $type='')
	{
		$this->setMessage(new Message($data, $position, $type, 1));
	}
	public function setRedirect($url, $data='', $position='', $type='')
	{
		$this->setMessage(new Message($url.(preg_match('!\?!', $url) ? '&' : '?').'data='.base64_encode(htmlspecialchars($data)).'&position='.base64_encode($position).'&type='.$type, $position, $type, 2));
	}
	public function setMessage($message)
	{
		$this->logger($message);
		$this->messages = array();
		$this->messages[] = $message;
	}
	
	public function logger($message) {
		$fd = fopen(MessageLog, 'a+');
		fwrite($fd, '[Message '.$message->getNomEtat().' ('.$message->getType().', '.$message->getPosition().') '.date('j/m/Y G\hi\ms', time()).'] '.$message->getData()."\n");
		fclose($fd);
	}
}

?>