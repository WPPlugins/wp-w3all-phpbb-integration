<?php
/**
 * Bcrypt class
 * 
 * @author Christian Metz
 * @since 23.06.2012
 * @copyright Christian Metz - MetzWeb Networks 2012
 * @version 1.0
 * @license BSD http://www.opensource.org/licenses/bsd-license.php
 */
//class Bcrypt {
class w3_Bcrypt {
  /**
   * Work cost factor
   * range between [04; 31]
   * 
   * @var string
   */
  private static $_workFactor = 10;
  /**
   * Default identifier
   * 
   * @var string
   */
  private static $_identifier = '2y';
  /**
   * All valid hash identifiers
   * 
   * @var array
   */
  private static $_validIdentifiers = array ('2a', '2x', '2y');
  /**
   * Hash password
   * 
   * @param string $password
   * @param integer [optional] $workFactor
   * @return string
   */
  public static function hashPassword($password, $workFactor = 0) {
    if (version_compare(PHP_VERSION, '5.3') < 0) {
      throw new Exception('Bcrypt requires PHP 5.3 or above');
    }
    
    $salt = self::_genSalt($workFactor);
    return crypt($password, $salt);
  }
  /**
   * Check bcrypt password
   * 
   * @param string $password
   * @param string $storedHash
   * @return boolean
   */
  public static function checkPassword($password, $storedHash) {
    if (version_compare(PHP_VERSION, '5.3') < 0) {
      throw new Exception('Bcrypt requires PHP 5.3 or above');
    }
  
    self::_validateIdentifier($storedHash);
    $checkHash = crypt($password, $storedHash);

    return ($checkHash === $storedHash);
  }
  /**
   * Generates the salt string
   * 
   * @param integer $workFactor
   * @return string
   */
  private static function _genSalt($workFactor) {
    if ($workFactor < 4 || $workFactor > 31) {
      $workFactor = self::$_workFactor;
    }
    
    $input = self::_getRandomBytes();
    $salt = '$' . self::$_identifier . '$';
    
    $salt .= str_pad($workFactor, 2, '0', STR_PAD_LEFT);
    $salt .= '$';
    
    $salt .= substr(strtr(base64_encode($input), '+', '.'), 0, 22);
    
    return $salt;
  }
  
/**
   * OpenSSL's random generator
   * 
   * @return string
   */
 private static function _getRandomBytes() {

  if (!function_exists('openssl_random_pseudo_bytes')) {
  	   $input = self::get_random_salt($length = 16, $rand_seed = '');
  	 if(empty($input) OR $input < 16){
  		$input = substr(md5(mt_rand(5, 15) . microtime()),16);
  	 }
  	 return $input;
   }
    return openssl_random_pseudo_bytes(16);
  }
  
  /**
   * Validate identifier
   * 
   * @param string $hash
   * @return void
   */
  private static function _validateIdentifier($hash) {
    if (!in_array(substr($hash, 1, 2), self::$_validIdentifiers)) {
    	   	    	return false; // fix for unsupported phpBB hash format: if old phpBB hash not recognized, this will cause WP to show pass not recognized message to the wp user onlogin
      throw new Exception('Unsupported hash format.');
    }
  }
  
  /**
	* Return unique id
	*
	* @param string $extra Additional entropy
	*
	* @return string Unique id
	*/
public static function unique_id($extra = 'c')
	{
		$phpbb_config = unserialize(W3PHPBBCONFIG);
		static $dss_seeded = false;
		

		$val = $phpbb_config['rand_seed'] . microtime();
		$val = md5($val);
		$phpbb_config['rand_seed'] = md5($phpbb_config['rand_seed'] . $val . $extra);

		if ($dss_seeded !== true && ($phpbb_config['rand_seed_last_update'] < time() - rand(1,10)))
		{
			// should update rand seed values in phpBB
			//$this->config->set('rand_seed_last_update', time(), true);
			//$this->config->set('rand_seed', $this->config['rand_seed'], true);
			$dss_seeded = true;
		}

		return substr($val, 4, 16);
	}

	/**
	* Get random salt with specified length
	*
	* @param int $length Salt length
	* @param string $rand_seed Seed for random data (optional). For tests.
	*
	* @return string Random salt with specified length
	*/
public static function get_random_salt($length, $rand_seed = '/dev/urandom')
	{
		$random = '';

		if (($fh = @fopen($rand_seed, 'rb')))
		{
			$random = fread($fh, $length);
			fclose($fh);
		}

		if (strlen($random) < $length)
		{
			$random = '';
			$random_state = self::unique_id();

			for ($i = 0; $i < $length; $i += 16)
			{
				$random_state = md5(self::unique_id() . $random_state);
				$random .= pack('H*', md5($random_state));
			}
			$random = substr($random, 0, $length);
		}
		return $random;
	}

  
}