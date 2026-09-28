<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Register extends Common
{
    public string $table = 'register_account_temp';
    public ?string $email = null;
    public ?string $username = null;
    public ?string $password = null;
    public ?string $token = null;
    public ?string $avatar = 'default.png';
    public ?string $expDate = null;
    public ?string $details = null;
    public ?string $details_opt = null;
}