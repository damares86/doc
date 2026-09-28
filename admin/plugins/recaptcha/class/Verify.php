<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Verify extends Common
{
    public string $table = 'verify';
    public ?string $secret = null;
    public ?string $public = null;
}