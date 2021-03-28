<?php

namespace IPay88\View;

class RequestForm
{
	public static function render($fieldValues, $paymentUrl, $isSubmitOnLoad=true)
	{
		echo "<form id='autosubmit' action='".$paymentUrl."' method='post'>";
		if (is_array($fieldValues) || is_object($fieldValues))
		{
			foreach ($fieldValues as $key => $val) {
			    echo "<input type='hidden' name='".ucfirst($key)."' value='".htmlspecialchars($val)."'>";
			}
		}

		if (!$isSubmitOnLoad) {
			echo '<input type="submit" value="Submit">';
		}
		echo "</form>";
		echo "
		<script type='text/javascript'>
		    function submitForm() {
		        document.getElementById('autosubmit').submit();
		    }
        ";
		if ($isSubmitOnLoad) {
			echo "    window.onload = submitForm;";
		}
		echo "</script>";
	}
}
