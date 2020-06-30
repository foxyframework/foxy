<?php
/**
 * @version     1.0.0 Foxy Framework $
 * @package     Foxy Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    Foy Team
 * @website	    https://foxyframework.github.io/foxysite/
 *
*/

defined('_Foxy') or die ('restricted access');

class Mailer {
	
	const STRIP_RETURN_PATH = TRUE;
	
	private $to = NULL;
	private $subject = NULL;
	private $textMessage = NULL;
	private $headers = NULL;
	
	private $recipients = NULL;
	private $cc = NULL;
	private $cco = NULL;
	private $from = NULL;
	private $replyTo = NULL;
	private $attachments = array();
	
	public function __construct($to = NULL, $subject = NULL, $textMessage = NULL, $headers = NULL) {
		self::$to = $to;
		self::$recipients = $to;
		self::$subject = $subject;
		self::$textMessage = $textMessage;
		self::$headers = $headers;
	}
	
	public static function send() {
		if (is_null(self::$to)) {
			throw new Exception("Must have at least one recipient.");
		}
		
		if (is_null(self::$from)) {
			throw new Exception("Must have one, and only one sender set.");
		}
		
		if (is_null(self::$subject)) {
			throw new Exception("Subject is empty.");
		}
		
		if (is_null(self::$textMessage)) {
			throw new Exception("Message is empty.");
		}
		
		self::packHeaders();
		$sent = mail(self::$to, self::$subject, self::$textMessage, self::$headers);
		if(!$sent) {
			$errorMessage = "Server couldn't send the email.";
			throw new Exception($errorMessage);
		} else {
			return true;
		}
	}
	
	public static function addRecipient($name, $address) {
		self::$recipients .= (is_null(self::$recipients)) ?  ("$name <$address>") : (", " . "$name <$address>");
		self::$to .= (is_null(self::$to)) ?  $address : (", " . $address);
	}
	
	public static function addCC($name, $address) {
		self::$cc .= (is_null(self::$cc)) ? ("$name <$address>") : (", " . "$name <$address>");
	}
	
	public static function addCCO($name, $address) {
		self::$cc .= (is_null(self::$cc)) ? ("$name <$address>") : (", " . "$name <$address>");
	}
	
	public static function setFrom($name, $address) {
		self::$from = "$name <$address>" . PHP_EOL;
		if (is_null(self::$replyTo)) {
			self::$replyTo = $address. PHP_EOL;
		}
	}
	
	public static function setReplyTo($address) {
		self::$replyTo = $address . PHP_EOL;
	}
	
	public static function Subject($subject) {
		self::$subject = $subject;
	}
	
	public static function Body($textMessage) {
		self::$textMessage = $textMessage;
	}
	
	public static function attachFile($filePath) {
		self::$attachments[] = $filePath;
	}
	
	private function packHeaders() {
		if (!self::$headers) {
			self::$headers = "MIME-Version: 1.0" . PHP_EOL;
			self::$headers .= "To: " . self::$recipients . PHP_EOL;
			self::$headers .= "From: " . self::$from . PHP_EOL;
			
			if (self::STRIP_RETURN_PATH !== TRUE) {
				self::$headers .= "Reply-To: " . self::$replyTo . PHP_EOL;
				self::$headers .= "Return-Path: " . self::$from . PHP_EOL;
			}
			
			if (self::$cc) {
				self::$headers .= "Cc: " . self::$cc . PHP_EOL;
			}
			
			if (self::$cco) {
				self::$headers .= "Bcc: " . self::$cco . PHP_EOL;
			}
			
			$str = "";
			
			if (self::$attachments) {
				$random_hash = md5(date('r', time()));
				$headers .= "Content-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"" . PHP_EOL;
				
				$pos = strpos(self::$textMessage, "<html>");
				if ($pos === false) {
					$str .= "--PHP-mixed-$random_hash" . PHP_EOL;
					$str .= "Content-Type: text/plain; charset=\"utf-8\"" . PHP_EOL;
					$str .= "Content-Transfer-Encoding: 7bit" . PHP_EOL;
					$str .= self::$textMessage . PHP_EOL;
				}
				
				if ($pos == 0) {
					$str .= "--PHP-mixed-$random_hash" . PHP_EOL;
					$str .= "Content-Type: text/html; charset=\"utf-8\"" . PHP_EOL;
					$str .= "Content-Transfer-Encoding: 7bit" . PHP_EOL;
					$str .= self::$textMessage . PHP_EOL;
				}
				
				if ($pos > 0) {
					$str .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"" . PHP_EOL;
					$str .= "--PHP-alt-$random_hash" . PHP_EOL;
					$str .= "Content-Type: text/plain; charset=\"utf-8\"" . PHP_EOL;
					$str .= "Content-Transfer-Encoding: 7bit";
					$str .= substr(self::$textMessage, 0, $pos);
					$str .= PHP_EOL;
					$str .= "--PHP-alt-$random_hash" . PHP_EOL;
					$str .= "Content-Type: text/html; charset=\"utf-8\"" . PHP_EOL;
					$str .= "Content-Transfer-Encoding: 7bit";
					$str .= substr(self::$textMessage, $pos);
					$str .= "--PHP-alt-$random_hash--" . PHP_EOL;
				}
				
				foreach (self::$attachments as $key => $value) {
					$mime_type = mime_content_type($value);
					//$mime_type = "image/jpeg";
					$attachment = chunk_split(base64_encode(file_get_contents($value)));
					$fileName = basename("$value");
					$str .= "--PHP-mixed-$random_hash" . PHP_EOL;
					$str .= "Content-Type: $mime_type; name=\"$fileName\"" . PHP_EOL;
					$str .= "Content-Disposition: attachment" . PHP_EOL;
					$str .= "Content-Transfer-Encoding: base64" . PHP_EOL;
					$str .= PHP_EOL;
					$str .= "$attachment";
					$str .= PHP_EOL;
				}
				$str .= "--PHP-mixed-$random_hash--" . PHP_EOL;
			} else {
				$pos = strpos(self::$textMessage, "<html>");
				if ($pos === false) {
					$headers .= "Content-Type: text/plain; charset=\"utf-8\"" . PHP_EOL;
					$headers .= "Content-Transfer-Encoding: 7bit";
					$str .= self::$textMessage . PHP_EOL;
				}
				
				if ($pos === 0) {
					$headers .= "Content-Type: text/html; charset=\"utf-8\"" . PHP_EOL;
					$headers .= "Content-Transfer-Encoding: 7bit";
					$str .= self::$textMessage . PHP_EOL;
				}
				
				if ($pos > 0) {
					$random_hash = md5(date('r', time()));
					$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"" . PHP_EOL;
					$str .= "--PHP-alt-$random_hash" . PHP_EOL;
					$str .= "Content-Type: text/plain; charset=\"utf-8\"" . PHP_EOL;
					$str .= "Content-Transfer-Encoding: 7bit";
					$str .= substr(self::$textMessage, 0, $pos);
					$str .= PHP_EOL;
					$str .= "--PHP-alt-$random_hash" . PHP_EOL;
					$str .= "Content-Type: text/html; charset=\"utf-8\"" . PHP_EOL;
					$str .= "Content-Transfer-Encoding: 7bit";
					$str .= substr(self::$textMessage, $pos);
					$str .= "--PHP-alt-$random_hash--" . PHP_EOL;
				}
			}
			self::$headers = $headers;
			self::$textMessage = $str;
		}
	}
}

/* usage */
/*
$myMessage = "<html>...";
try {
	// minimal requirements to be set
	mailer::setFrom("My Website", "contact@mywebsite.com");
	mailer::setRecipient("Holly","holly@email.com");
	mailer::Subject("About stuff");
	mailer::Body($myMessage);
	
	// options below are completely optional
	mailer::addRecipient("Marcus", "marcus@anothermail.org");
	mailer::addCC("Mr. Carlson", "manager@business.com");
	mailer::addCCO("Mr. X", "mistery@mindmail.com");
	mailer::attachFile('../files/file1.txt');
	
	// now we send it!
	mailer::send();
} catch (Exception $e) {
	echo $e->getMessage();
	exit(0);
}

echo "Success!";
*/
?>
