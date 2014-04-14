<?php
namespace Bnoger\Service\Security;

abstract class PasswordCheck
{

    /**
     * Hash a password in order to store it
     * 
     * @param string $input
     *            Plain text password
     */
    public abstract function hash($input);

    /**
     * Verify a password
     * 
     * @param string $input
     *            Plain text password
     * @param string $existingHash
     *            Existing hashed password
     */
    public abstract function verify($input, $existingHash);
}
