<?php
//
// Definition of CoolZipImportimport class
//
// Created on: <18-Nov-2006 12:00:00 ekke>
//
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.0
// BUILD VERSION: 17785
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

/*! \file coolzipimportimport.php
 */

/*!
 \class CoolZipImportimport coolzipimportimport.php
 \brief The class CoolZipImportimport does

 */
/*function microtime_float()
 {
 list($usec, $sec) = explode(" ", microtime());
 return ((float)$usec + (float)$sec);
 }*/

//$time_start = microtime_float();

// Sleep for a while
//usleep(100);





class CoolZipImportImport
{
	/*!
	 Constructor
	 */

	function CoolZipImportImport()
	{
	}

	/*!
	 Imports an zip with images document from the given file.
	 */
	function import( $file, $placeNodeID )
	{
  	
		//echo $placeNodeID;
		$importResult = array();
		include_once( "lib/ezfile/classes/ezdir.php" );
		$unzipResult = "";
  
		eZDir::mkdir( $this->ImportDir );

		$http =& eZHTTPTool::instance();

		$file = $http->sessionVariable( "coolzipimport_import_filename" );
		$originalFilename = $http->sessionVariable( "coolzipimport_import_original_filename" );


		// Check if zlib extension is loaded, if it's loaded use bundled ZIP library,
		// if not rely on the unzip commandline version.
		if ( !function_exists( 'gzopen' ) )
		{
			exec( "unzip -o $file -d " . $this->ImportDir, $unzipResult );
		}
		else
		{
			require_once('extension/coolzipimport/lib/pclzip.lib.php');
			$archive = new PclZip( $file );
			$archive->extract( PCLZIP_OPT_PATH, $this->ImportDir );
		}

		$fileName = $this->ImportDir . $originalFilename;


		$lalilu = eZDir::findSubitems ($this->ImportDir);







		function saveImage ($placeNodeID, $h1Tag, $notesTag, $href, $altTag, $hrefPur){
			// Import image
			if ( file_exists( $href ) )
			{

				$classID = 5;
				$class =& eZContentClass::fetch( $classID );
				$creatorID = 14;
				$parentNodeID = $placeNodeID;
				// $parentNodeID = $subNode;

				$contentObject =& $class->instantiate( $creatorID, 1 );

				$nodeAssignment =& eZNodeAssignment::create( array(
                                                             'contentobject_id' => $contentObject->attribute( 'id' ),
                                                             'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                             'parent_node' => $parentNodeID,
                                                             'sort_field' => 2,
			                                                 'sort_order' => 0,
			                                                 'is_main' => 1

			                                                 )
			                                                 );
			                                                 $nodeAssignment->store();

			                                                 $version =& $contentObject->version( 1 );
			                                                 $version->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
			                                                 $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );

			                                                 $version->store();

			                                                 $contentObjectID = $contentObject->attribute( 'id' );
			                                                 $dataMap =& $contentObject->dataMap();

			                                                 $dataMap['name']->setAttribute( 'data_text', $h1Tag );
			                                                 $dataMap['name']->store();

			                                                 $dataMap['caption']->setAttribute( 'data_text', $notesTag );
			                                                 $dataMap['caption']->store();

			                                                 $imageContent =& $dataMap['image']->attribute( 'content' );
			                                                 $imageContent->initializeFromFile( $href , $altTag , $hrefPur);
			                                                 $dataMap['image']->store();



			                                                 include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
			                                                 $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                                   'version' => 1 ) );

                                                                                                   $mypriorityID = $contentObjectID;

                                                                                                   return $mypriorityID;
   
			} //if file exist
		}

		$myprioritycounter = 0;
		$level = 1;

		foreach ( $lalilu as $childNode )
		{

			$hrefPur = $childNode;

			$href = $this->ImportDir . $childNode;
  
			// ( $sectionNode->children() as $childNode )
			$altTag = "";
			$notesTag = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>"."<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\" "." xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" "." xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />";
			$h1Tag = $childNode;
			$aTag = "";
			$altTag = $childNode;
			//    $notesTag = cleanString(array_to_str($notesContent[$myprioritycounter]));
			//    $h1Tag = cleanString(array_to_str($h1Content[$myprioritycounter]));
			$aTag = "";




			$priorityID[] =	saveImage ($placeNodeID, $h1Tag, $notesTag, $href, $altTag, $hrefPur);

			$priority[] = $myprioritycounter;
			$myprioritycounter++;

		} // end  foreach ( $sectionNodeArray as $sectionNode )

		include_once( 'kernel/classes/ezcache.php' );
		eZCache::clearByTag( 'content' );

		include_once( "lib/ezfile/classes/ezdir.php" );

		eZDir::recursiveDelete( $this->ImportDir );
		return $importResult;

	} // funct import end





	var $ImportDir = "var/cache/coolzipimport/import/";
	var $mypriorityID;
	var $priorityID;
}
//$time_end = microtime_float();
//echo $time = $time_end - $time_start;
//echo "Did nogthin in $time seconds\n";
  ?>
