<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Calendar extends Common
{
    public string $table = 'calendar_events';
    public ?string $event_title = null;
    public ?string $page_origin = null;
    public int|string|null $cat_id = null;
    public ?string $color = null;
    public ?string $cat_name = null;
    public ?string $cat_color = null;
    public ?string $start = null;
    public ?string $end = null;
    public ?string $note = null;
    public ?string $url = null;
}