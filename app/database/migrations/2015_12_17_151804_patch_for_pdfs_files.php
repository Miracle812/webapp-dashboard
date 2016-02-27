<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatchForPdfsFiles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		function findAndFix($content)
		{
			preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $content, $matches);
			if(isset($matches[1])){
				$fullsrc = $matches[1];
				preg_match('/^.*\.(pdf|Pdf|PDF)$/i', $fullsrc, $files);
				if(isset($files[1])){
					$newSrc = '//docs.google.com/gview?url='.$files[0].'&amp;embedded=true';
					$newContent = str_replace($fullsrc,$newSrc,$content);
				}
			}
			return isset($newContent) ? $newContent : $content;
		}

		$haccps = \Model\Haccp::all();
		foreach($haccps as $haccp){
			$haccp->content = findAndFix($haccp->content);
			$haccp->hazards = findAndFix($haccp->hazards);
			$haccp->control = findAndFix($haccp->control);
			$haccp->monitoring = findAndFix($haccp->monitoring);
			$haccp->corrective_action = findAndFix($haccp->corrective_action);
			$haccp->update();
		}
		$knowledges = \Model\Knowledges::all();
		foreach($knowledges as $knowledge){
			$knowledge->content_one = findAndFix($knowledge->content_one);
			$knowledge->content_two = findAndFix($knowledge->content_two);
			$knowledge->update();
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
