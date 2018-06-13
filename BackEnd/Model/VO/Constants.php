<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 24/05/2018
 * Time: 12:54
 */

namespace Ciente\Model\VO;


class Constants
{
   //web path
    const PROFILE_IMAGE_WEB_PATH = '/storage/pmropadrao/profile/';
    const CIENTE_STORAGE_WEB_PATH = '/storage/ciente/';

    //file system path
    const STORAGE_DIRECTORY = 'storage';
    const PROFILE_IMAGE_DIRECTORY = 'profile';
    const SERVER_PATH = '/var/www/html';
    const PMRO_PADRAO_PATH_FULL = Constants::SERVER_PATH .'/pmroPadrao';
    const STORAGE_PATH = '/storage';
    const STORAGE_PATH_FULL = Constants::SERVER_PATH . Constants::STORAGE_PATH;
    const PROFILE_IMAGE_PATH_FULL = Constants::STORAGE_PATH_FULL .'/pmropadrao/profile';
    const CIENTE_STORAGE_PATH =  Constants::STORAGE_PATH_FULL.'/ciente';
}