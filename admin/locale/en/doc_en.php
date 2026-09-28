<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

$reg_mail_subject = "Appointment booking - ";
$doc_block1 = '<html><body>';
$doc_block1 .= '<p>Hello,</p>';
$doc_block1 .= '<p>Your appointment has been confirmed. Details:</p>';

$doc_block2 = '<p>If you cannot attend, please cancel your appointment using the following link:<br></p>';

$doc_block3 = '<p>Thank you,<br>Dr. Crosetto Maria Chiara</p>';
$doc_block3 .= '<p>-----------------------------------------------------------</p>';
$doc_block3 .= '<p>NOTE: this is an automated email, do not reply directly. To contact the doctor, please visit the website.</p>';
$doc_block3 .= '</body></html>';

// Messages
$msg_timetableUpdate = "Practice timetable updated successfully";
$msg_sentBookingMail = "Booking confirmed. A summary email has been sent to your address";
$msg_bookingDeleteSucc = "Booking deleted successfully. Thank you";
$msg_sentContactMail = "Contact email sent successfully";
$msg_msgUpdate = "Homepage notice updated successfully";

// Errors
$err_timetableUpdateErr = "Error updating practice timetable";
$err_bookingExists = "Error: the selected time slot is no longer available";
$err_errSendBookingMail = "Booking is confirmed, but there was an error sending the confirmation email";
$err_notBooked = "Error creating booking. Please try again";
$err_bookingDeleteFail = "Error deleting booking";
$err_errSendContactMail = "Error sending contact email. Please try again later";
$err_uploadError = "Error uploading attached file";
$err_fileTooLarge = "Attached file too large: maximum allowed size is 10MB";
$err_invalidFileType = "Invalid file format. Allowed formats: PDF, JPG, JPEG, PNG, WEBP, HEIC, HEIF";
$err_errRecaptcha = "reCAPTCHA verification failed. Please try again";
$err_noBooking = "No booking found for specified parameters";
$err_msgUpdateErr = "Error updating homepage notice";
