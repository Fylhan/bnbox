<?php
namespace Model;

use SimpleValidator\Validator;
use SimpleValidator\Validators;
use Core\Session;

/**
 * Section model
 *
 * @package model
 * @author Fylhan
 */
class Section extends Base
{

    /**
     * SQL table name
     *
     * @var string
     */
    const TABLE = 'section';

    /**
     * Return true if the section exists
     *
     * @access public
     * @param integer $user_id
     *            User id
     * @return boolean
     */
    public function exists($user_id)
    {
        return $this->db->table(self::TABLE)
            ->eq('id', $user_id)
            ->count() === 1;
    }

    /**
     * Get a specific section by id
     *
     * @access public
     * @param integer $user_id
     *            User id
     * @return array
     */
    public function getById($user_id)
    {
        return $this->db->table(self::TABLE)
            ->eq('id', $user_id)
            ->findOne();
    }

    /**
     * Get all sections
     *
     * @access public
     * @return array
     */
    public function getAll()
    {
        return $this->db->table(self::TABLE)
            ->columns('id', 'url', 'title', 'description')
            ->findAll();
    }

    /**
     * Get the number of sections
     *
     * @access public
     * @return integer
     */
    public function count()
    {
        return $this->db->table(self::TABLE)->count();
    }

    /**
     * List all (key-value pairs with url -> title)
     *
     * @access public
     * @return array
     */
    public function getList()
    {
        return $this->db->hashtable(self::TABLE)->getAll('url', 'title');
    }

    /**
     * Prepare values before an update or a create
     *
     * @access public
     * @param array $values
     *            Form values
     */
    public function prepare(array &$values)
    {}

    /**
     * Add a new section in the database
     *
     * @access public
     * @param array $values
     *            Form values
     * @return boolean integer
     */
    public function create(array $values)
    {
        $this->prepare($values);
        return $this->persist(self::TABLE, $values);
    }

    /**
     * Modify a section
     *
     * @access public
     * @param array $values
     *            Form values
     * @return array
     */
    public function update(array $values)
    {
        $this->prepare($values);
        $result = $this->db->table(self::TABLE)
            ->eq('id', $values['id'])
            ->update($values);
        
        return $result;
    }

    /**
     * Remove a specific section
     *
     * @access public
     * @param integer $id
     *            Section id
     * @return boolean
     */
    public function remove($id)
    {
        return $this->db->transaction(function ($db) use($id)
        {
            if (! $db->table(self::TABLE)
                ->eq('id', $id)
                ->remove()) {
                return false;
            }
        });
    }

    /**
     * Common validation rules
     *
     * @access private
     * @return array
     */
    private function commonValidationRules()
    {
        return array(
            new Validators\Required('url', t('Field required')),
            new Validators\Required('title', t('Field required'))
        );
    }

    /**
     * Validate section creation
     *
     * @access public
     * @param array $values
     *            Form values
     * @return array $valid, $errors [0] = Success or not, [1] = List of errors
     */
    public function validateCreation(array $values)
    {
        $v = new Validator($values, $rules, $this->commonValidationRules());
        
        return array(
            $v->execute(),
            $v->getErrors()
        );
    }

    /**
     * Validate section modification
     *
     * @access public
     * @param array $values
     *            Form values
     * @return array $valid, $errors [0] = Success or not, [1] = List of errors
     */
    public function validateModification(array $values)
    {
        $rules = array(
            new Validators\Required('id', t('The section id is required'))
        );
        
        $v = new Validator($values, array_merge($rules, $this->commonValidationRules()));
        
        return array(
            $v->execute(),
            $v->getErrors()
        );
    }
}
