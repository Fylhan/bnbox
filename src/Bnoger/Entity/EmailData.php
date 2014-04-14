<?php
/**
 * Un contact représente un email envoyé par un contact
 * @author     Fylhan
 * @copyright  Fylhan
 * @license    Fylhan
 */

class EmailData {
	/**
	 * 
	 * @var string
	 * @access protected
	 */
	protected  $nom=NULL;
	/**
	 * 
	 * @var string
	 * @access protected
	 */
	protected  $email;
	/**
	 * 
	 * @var string
	 * @access protected
	 */
	protected  $objet=NULL;
	/**
	 * 
	 * @var string
	 * @access protected
	 */
	protected  $message;
	/**
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected  $antibot = false;
	/**
	 * True si l'email a été envoyé
	 * @var boolean
	 * @access protected
	 */
	protected  $sent = false;
	/**
	 * Date d'envoie de l'email
	 * @var DateTime
	 * @access protected
	 */
	protected $sentDate = NULL;
	protected $expediteur;
	protected $destinataires;
	protected $destinatairesCc;
	protected $destinatairesCci;

	
	// --- Constructor
	/**
	 * @access public
	 * @param array $params 
	 */
	public  function __construct($params = array()) {
		$this->nom = isset($params['nom']) ? $params['nom'] : $this->nom;
		$this->email = @$params['email'];
		$this->objet = isset($params['objet']) ? $params['objet'] : $this->objet;
		$this->message = @$params['message'];
		$this->antibot = isset($params['antibot']) ? $params['antibot'] : $this->antibot;
		$this->sent = isset($params['sent']) ? $params['sent'] : $this->sent;
		// $this->sentDate = isset($params['sentDate']) ? $params['sentDate'] : $this->sentDate;
		$this->addDestinataire(EmailContact, SiteNom);
		if (isset($params['destinataire']) && NULL != $params['destinataire']) {
			if ('flambeaux' == $params['destinataire']) {
				$this->addDestinataire(EmailFlambeaux, 'Flambeaux de Saint-Maur');
			}
			elseif ('gdj' == $params['destinataire']) {
				$this->addDestinataire(EmailGDJ, 'GDJ de Saint-Maur');
			}
		}
		$this->prepareToSave();
	}
	
	/**
	* Vérifie la validité de cet élément
	* return true si les données sont valides
	* thrown Exception
	*/
	public function isValid() {
		$erreur = '';
		$erreurs = array();
		$champsManquants = array();
	
		// Vérification des data
		if (!isset($this->email) || $this->email == NULL) {
			$champsManquants[] = '<label for="email" class="error">Nous aurons besoin de votre email pour vous répondre.</label>';
		}
		if (isset($this->email) && $this->email != NULL && !preg_match('!^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$!i', $this->email)) {
			$erreurs[] = '<label for="email" class="error">Cette adresse email n\'est pas valide.</label>';
		}
		if (!isset($this->message) || $this->message == NULL) {
			$champsManquants[] = '<label for="message" class="error">Vous n\'auriez pas oublié d\'écrire votre message par hasard ?</label>';
		}
		if (!isset($this->antibot) || $this->antibot == NULL || (isset($this->antibot) && $this->antibot != NULL && $this->antibot != Antibot)) {
			$erreurs[] = '<label for="antibot" class="error">En répondant à la question antispam (dont la réponse est '.Antibot.'), vous nous permettez de vérifier que vous êtes bien humain et non un robot souhaitant nous envoyer du spam !</label>';
		}
	
		// Retour du message correspondant
		$nbManquant = count($champsManquants);
		$nbErreur = count($erreurs);
		if ($nbManquant > 0) {
			if ($nbManquant > 1)
				$erreur = '<li>Il semble que vous ayez oublié quelques champs indispensables : <ul><li>'.implode('</li><li>', $champsManquants).'</li></ul></li>';
			else
				$erreur = '<li>'.$champsManquants[0].'</li>';
		}
		if ($nbErreur > 0) {
			$erreur .= '<li>'.implode('</li><li>', $erreurs).'</li>';
		}
		
		if (NULL != $erreur && '' != $erreur) {
			if ($nbErreur+$nbManquant > 1) {
				$erreur = 'Merci de corriger les erreurs suivantes : <ul>'.$erreur.'</ul>';
			}
			else {
				$erreur = '<ul>'.$erreur.'</ul>';
			}
			throw new Exception($erreur, ERREUR_BLOQUANT);
		}
		return true;
	}
	
	public function prepareToPrint() {
		$this->prepareToPrintForm();
	}
	public function prepareToPrintForm() {
		$this->nom = deparserS($this->nom);
		$this->email = deparserS($this->email);
		$this->objet = deparserS($this->objet);
		$this->sentDate = deparserS($this->sentDate);
		$this->message = br2nl(deparserS($this->message));
		$this->antibot = parserI($this->antibot);
		$this->sent = parserI($this->sent);
	}
	public function prepareToSave() {
		$this->nom = strip_tags(parserS($this->nom));
		$this->email = strip_tags(parserS($this->email));
		$this->objet = strip_tags(parserS($this->objet));
		$this->message = strip_tags(parserS($this->message));
		$this->antibot = parserI($this->antibot);
		$this->sent = parserI($this->sent);
		$this->sentDate = parserS($this->sentDate);
	}


	// --- Get/Set
	public function getNom()
	{
	    return $this->nom;
	}

	public function setNom($nom)
	{
	    $this->nom = $nom;
	}

	public function getEmail()
	{
	    return $this->email;
	}

	public function setEmail($email)
	{
	    $this->email = $email;
	}

	public function getObjet()
	{
	    return $this->objet;
	}

	public function setObjet($objet)
	{
	    $this->objet = $objet;
	}

	public function getMessage()
	{
	    return $this->message;
	}

	public function setMessage($message)
	{
	    $this->message = $message;
	}

	public function getAntibot()
	{
	    return $this->antibot;
	}

	public function setAntibot($antibot)
	{
	    $this->antibot = $antibot;
	}

	public function getSent()
	{
	    return $this->sent;
	}

	public function setSent($sent)
	{
	    $this->sent = $sent;
	}

	public function getSentDate()
	{
	    return $this->sentDate;
	}

	public function setSentDate($sentDate)
	{
	    $this->sentDate = $sentDate;
	}

	public function getExpediteur() {
	    if (NULL == $this->expediteur || '' == $this->expediteur || (is_array($this->expediteur) && count($this->expediteur) == 0)) {
	    	$this->setExpediteur($this->email, $this->nom);
	    }
	    return $this->expediteur;
	}
	public function setExpediteur($email, $nom=NULL) {
		if (NULL != $nom && '' != $nom) {
			$this->expediteur[$nom] = $email;
		}
		else {
			$this->expediteur = $email;
		}
	}

	public function getDestinataires()
	{
	    return $this->destinataires;
	}
	public function setDestinataires($destinataires)
	{
	    $this->destinataires = $destinataires;
	}
	public function addDestinataire($email, $nom=NULL) {
		if (NULL != $nom && '' != $nom) {
			$this->destinataires[$nom] = $email;
		}
		else {
			$this->destinataires[] = $email;
		}
	}

	public function getDestinatairesCc() {
	    return $this->destinatairesCc;
	}
	public function setDestinatairesCc($destinatairesCc) {
	    $this->destinatairesCc = $destinatairesCc;
	}
	public function addDestinataireCc($email, $nom=NULL) {
		if (NULL != $nom && '' != $nom) {
			$this->destinatairesCc[$nom] = $email;
		}
		else {
			$this->destinatairesCc[] = $email;
		}
	}

	public function getDestinatairesCci() {
	    return $this->destinatairesCci;
	}
	public function setDestinatairesCci($destinatairesCci) {
	    $this->destinatairesCci = $destinatairesCci;
	}
	public function addDestinataireCci($email, $nom=NULL) {
		if (NULL != $nom && '' != $nom) {
			$this->destinatairesCci[$nom] = $email;
		}
		else {
			$this->destinatairesCci[] = $email;
		}
	}
}
?>