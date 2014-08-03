<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    
    const ERROR_ROLE_INVALID = 3;
    
    const ERROR_VERIFIED_INVALID = 4;
    
    private $_id;

	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */

    /*
	public function authenticate()
	{
		/*
        $users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
        *
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}

    */


    public function authenticate($type=null, $force_login = false) {
        $validRoles = array('user');
        switch(strtolower($type)){
            case 'social': // for social user login - use in HybridauthController.php
                $user=User::model()->findByAuthSocial($this->username, $this->password);
                break;
            case 'user':
                $criteria = new CDbCriteria;
                $criteria->compare('email',$this->username);
                $user = User::model()->find($criteria);
                break;
            default:
                $user=User::model()->find('LOWER(email)=?',array(strtolower($this->username)));
                break;
        }//end switch

        if($force_login) {
            $pwd = $this->password;
        } else {
            $pwd = User::model()->encrypt($this->password);
        }

        if($user===null) 
        {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } 
        else if ($user->password != $pwd) 
        {  
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } 
        else if ( !in_array($user->role, $validRoles))
        {
            $this->errorCode = self::ERROR_ROLE_INVALID;
        } 
        else if(!$user->is_verified) 
        {
            $this->errorCode = self::ERROR_VERIFIED_INVALID;
        }    
        else 
        {
            $this->_id = $user->id;
            if (null == $user->last_login_time)
            {
                $lastLogin = time();
            } 
            else 
            {
                $lastLogin = strtotime($user->last_login_time);
            }
            $this->setState('lastLoginTime', $lastLogin);
            $this->setState('role', $user->role);
            $this->setState('firstName',$user->first_name);
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }
}