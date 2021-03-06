<?php defined('SYSPATH') OR die('No direct access allowed.');

$lang = array
(
        // Change 'field' to the name of the actual field (e.g., 'email').
	'email' => array
		(
			'required' => 'Please enter a valid email.',
			'alpha'    => 'Only alphabetic characters are allowed.',
			'default'  => 'Email is not valid.',
		),
	'username' => array
		(
			'required' => 'Username cannot be empty',
			'alpha'    => 'Only alphabetic characters are allowed.',
			'default'  => 'Invalid Input.',
		),
	'name' => array
		(
			'required' => 'Name cannot be empty.',
			'alpha'    => 'Only alphabetic characters are allowed.',
			'default'  => 'Name must contain only alphabetic characters.',
		),
	'password' => array
		(
			'required' => 'Password cannot be empty.',
			'alpha'    => 'Only alphabetic characters are allowed.',
			'default'  => 'Invalid Input.',
		),
	'beta' => array
		(
			'required' => 'Beta Code is required',
			'alpha'    => 'Only alphabetic characters are allowed.',
			'default'  => 'Sorry your beta code is invalid',
		),
);