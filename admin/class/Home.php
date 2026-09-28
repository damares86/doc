<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Home extends Common
{
    public string $table = 'home';
    public ?string $content = null;
    public int|string|null $size = null;
}