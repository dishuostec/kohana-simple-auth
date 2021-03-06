<?php defined('SYSPATH') or die('No direct access allowed.');
class Model_Auth_Member extends ORM
{
  protected $_table_name = 'member';

  protected $_belongs_to = array();

  protected $_has_many = array();

  public function labels()
  {
    return array();
  }

  public function rules()
  {
	return array(
	  'username' => array(
		array('not_empty'),
		array('max_length', array(':value', 20)),
	  ),
	  'password' => array(
		array('not_empty'),
		array('max_length', array(':value', 32)),
	  ),
	  'realname' => array(
		array('not_empty'),
		array('max_length', array(':value', 50)),
	  ),
	);
  }

  public function filters()
  {
	return array(
	  'username' => array(
		array('htmlspecialchars')
	  ),
	  'realname' => array(
		array('htmlspecialchars')
	  )
	);
  }

	public function unique_key($value)
	{
		return 'username';
  }

	public function complete_login()
	{
		if ($this->_loaded)
		{
			// Update the number of logins
			$this->logins = new Database_Expression('logins + 1');

			// Set the last login date
			$this->last_login = date('Y-m-d H:i:s');

			// Save the user
			$this->update();
		}
  }

  public function complete_logout() {}

	public function unique_key_exists($value, $field = NULL)
	{
		if ($field === NULL)
		{
			// Automatically determine field by looking at the value
			$field = $this->unique_key($value);
		}

		return (bool) DB::select(array('COUNT("*")', 'total_count'))
			->from($this->_table_name)
			->where($field, '=', $value)
			->where($this->_primary_key, '!=', $this->pk())
			->execute($this->_db)
			->get('total_count');
	}

	public static function get_password_validation($values)
	{
		return Validation::factory($values)
			->rule('password', 'min_length', array(':value', 6));
	}

  public function create_user($values, $expected)
  {
    $extra_validation = Model_Member::get_password_validation($values)
      ->rule('password', 'not_empty')
      ->labels($this->labels());

    return $this->values($values, $expected)->create($extra_validation);
  }

  public function update_user($values, $expected = NULL)
  {
    if (empty($values['password']))
    {
      unset($values['password']);
    }

    // Validation for passwords
    $extra_validation = Model_Member::get_password_validation($values)
      ->labels($this->labels());

    return $this->values($values, $expected)->update($extra_validation);
  }

	public static function get_password_confirm_validation($values)
  {
    return Validation::factory($values)
      ->rule('password', 'min_length', array(':value', 6))
      ->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
  }

  public function change_password(array $values, $expected_old_password_key = 'password_old')
  {
    if ( ! Member::instance()->check_password($values[$expected_old_password_key])) {
      $valid = Validation::factory(array());
      $valid
        ->labels($model->labels())
        ->error('password', 'old_password_error');
      $exception = new ORM_Validation_Exception($model->errors_filename(), $valid);

      throw $exception;
    }

    $extra_validation = Model_Member::get_password_confirm_validation($values)
      ->labels($this->labels());

    return $this->values($values, array('password'))->update($extra_validation);
  }

}
