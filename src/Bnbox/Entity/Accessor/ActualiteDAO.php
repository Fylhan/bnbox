<?php
namespace Bnbox\Entity\Accessor;

use Bnbox\Entity\Actualite;

interface ActualiteDAO
{

    public function findActualiteById($id);

    public function findActualites($page = 1, $memeLesDesactives = false, $nbParPage = NbParPage);

    public function calculNbActualites($memeLesDesactives = false);

    public function updateActualite(Actualite $actualite);

    public function deleteActualite($id);

    public function deleteActualites($ids);
}
