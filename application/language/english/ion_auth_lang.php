<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Lang - English
*
* Author: Ben Edmunds
*         ben.edmunds@gmail.com
*         @benedmunds
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.14.2010
*
* Description:  English language file for Ion Auth messages and errors
*
*/

// Account Creation
$lang['account_creation_successful']            = 'Akun Berhasil dibuat';
$lang['account_creation_unsuccessful']          = 'Tidak dapat membuat akun';
$lang['account_creation_duplicate_email']       = 'Email sudah terdaftar/ tidak valid';
$lang['account_creation_duplicate_identity']    = 'Identity Already Used or Invalid';
$lang['account_creation_missing_default_group'] = 'Default group is not set';
$lang['account_creation_invalid_default_group'] = 'Invalid default group name set';


// Password
$lang['password_change_successful']          = 'Password telah berhasil diubah';
$lang['password_change_unsuccessful']        = 'Gagal ubah password, silahkan coba lagi';
$lang['forgot_password_successful']          = 'Reset Password telah dikirim ke email Anda';
$lang['forgot_password_unsuccessful']        = 'Gagal reset password, silahkan coba lagi';

// Activation
$lang['activate_successful']                 = 'Akun berhasil dibuat';
$lang['activate_unsuccessful']               = 'Gagal membuat akun';
$lang['deactivate_successful']               = 'Akun dinonaktifkan';
$lang['deactivate_unsuccessful']             = 'Akun tidak dapat dinonaktifkan';
$lang['activation_email_successful']         = 'Email aktivasi terkirim';
$lang['activation_email_unsuccessful']       = 'Email aktivasi tidak terkirim';

// Login / Logout
$lang['login_successful']                    = 'Login berhasil';
$lang['login_unsuccessful']                  = 'Gagal login';
$lang['login_unsuccessful_not_active']       = 'Akun Anda tidak aktif';
$lang['login_timeout']                       = 'Akun Anda dikunci sementara';
$lang['logout_successful']                   = 'Logout berhasil';

// Account Changes
$lang['update_successful']                   = 'Akun berhasil diupdate';
$lang['update_unsuccessful']                 = 'Gagal mengupdate akun';
$lang['delete_successful']                   = 'User telah dihapus';
$lang['delete_unsuccessful']                 = 'Tidak dapat menghapus user';

// Groups
$lang['group_creation_successful']           = 'Group created Successfully';
$lang['group_already_exists']                = 'Group name already taken';
$lang['group_update_successful']             = 'Group details updated';
$lang['group_delete_successful']             = 'Group deleted';
$lang['group_delete_unsuccessful']           = 'Unable to delete group';
$lang['group_delete_notallowed']             = 'Can\'t delete the administrators\' group';
$lang['group_name_required']                 = 'Group name is a required field';
$lang['group_name_admin_not_alter']          = 'Admin group name can not be changed';

// Activation Email
$lang['email_activation_subject']            = 'Account Activation';
$lang['email_activate_heading']              = 'Activate account for %s';
$lang['email_activate_subheading']           = 'Please click this link to %s.';
$lang['email_activate_link']                 = 'Activate Your Account';

// Forgot Password Email
$lang['email_forgotten_password_subject']    = 'Permintaan Reset Password';
$lang['email_forgot_password_heading']       = 'Hi, %s.';
$lang['email_forgot_password_subheading']    = 'Please click this link to %s.';
$lang['email_forgot_password_link']          = 'Reset Password Anda';

// New Password Email
$lang['email_new_password_subject']          = 'New Password';
$lang['email_new_password_heading']          = 'New Password for %s';
$lang['email_new_password_subheading']       = 'Your password has been reset to: %s';
