<?php

//region Using
use App\Middleware\GuestMiddleware;
use App\Middleware\MemberMiddleware;
use App\Middleware\ManagementGuestMiddleware;
use App\Middleware\ManagementMiddleware;
//endregion

$app->get('/','HomeController:index')->setName('home');

//region Member
$app->group('', function (){
    $this->get('/kayit-ol','RegisterController:getIndex')->setName('register');
    $this->post('/kayit-ol','RegisterController:postIndex');

    $this->get('/giris','LoginController:getIndex')->setName('login');
    $this->post('/giris','LoginController:postIndex');
})->add(new GuestMiddleware($container));

$app->group('', function (){
    $this->get('/sifre-degistir','MemberController:getChangePassword')->setName('member-changepassword');
    $this->post('/sifre-degistir','MemberController:postChangePassword');

    $this->get('/cikis','MemberController:getLogout')->setName('logout');
})->add(new MemberMiddleware($container));
//endregion

//region Management Panel
$app->group('/management', function (){
    //region Management
    $this->get('/login','Management\LoginController:getIndex')->setName('management-login');
    $this->post('/login','Management\LoginController:postIndex');
    //endregion
})->add(new ManagementGuestMiddleware($container));

$app->group('/management', function (){
    $this->get('','Management\HomeController:getIndex')->setName('management');

    //region Admins
    //region Home
    $this->get('/admin','Management\AdminController:getIndex')->setName('management-admin');
    //endregion
    //region New
    $this->get('/admin/new','Management\AdminController:getNew')->setName('management-admin-new');
    $this->post('/admin/new','Management\AdminController:postNew');
    //endregion
    //region Delete
    $this->get('/admin/delete/{id:[0-9]+}','Management\AdminController:getDelete')->setName('management-admin-delete');
    //endregion
    //region Edit
    $this->get('/admin/edit/{id:[0-9]+}','Management\AdminController:getEdit')->setName('management-admin-edit');
    $this->post('/admin/edit/{id:[0-9]+}','Management\AdminController:postEdit');
    //endregion
    //endregion

    //region Members
    //region Home
    $this->get('/member','Management\MemberController:getIndex')->setName('management-member');
    //endregion
    //region New
    $this->post('/member/new','Management\MemberController:postNew')->setName('management-member-new');
    //endregion
    //region Search
    $this->post('/member/search','Management\MemberController:postSearch')->setName('management-member-search');
    //endregion
    //region Delete
    $this->get('/member/delete/{id:[0-9]+}','Management\MemberController:getDelete')->setName('management-member-delete');
    //endregion
    //region Edit
    $this->get('/member/edit/{id:[0-9]+}','Management\MemberController:getEdit')->setName('management-member-edit');
    $this->post('/member/edit/{id:[0-9]+}','Management\MemberController:postEdit');
    //endregion
    //endregion

    //region Change Password
    $this->get('/change-password','Management\ChangePasswordController:getIndex')->setName('management-changepassword');
    $this->post('/change-password','Management\ChangePasswordController:postIndex');
    //endregion

    //region Logout
    $this->get('/logout','Management\LogoutController:getIndex')->setName('management-logout');
    //endregion
})->add(new ManagementMiddleware($container));
//endregion