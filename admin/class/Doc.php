<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Doc extends Common
{

    public $name;
    public $value;
    public $weekday ;
    public $start_time ;
    public $end_time ;
    public $booking ;


    function updateValue()
    {


        $query = "UPDATE " . $this->prx . $this->table . "
        SET  value = :value  WHERE name = :name";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":value", $this->value);
        $stmt->bindParam(":name", $this->name);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

     public function generateSlots($start, $end, $duration = 15)
    {
        $slots = [];

        $startTime = new DateTime($start);
        $endTime = new DateTime($end);

        while ($startTime < $endTime) {
            $slots[] = $startTime->format('H:i');
            $startTime->modify("+{$duration} minutes");
        }

        return $slots;
    }
}
