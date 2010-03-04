<?php

	Class datasourceNestedCats extends Datasource{

		function __construct(&$parent){
			parent::__construct($parent);
		}

		function example(){
			return '	<nested-cats>
		<main-tree>
			<item id="7" parent-id="1" level="1">Fruits</item>
			<item id="8" parent-id="7" level="2">Apples</item>
			<item id="9" parent-id="7" level="2">Bananas</item>
			<item id="10" parent-id="1" level="1">Animals</item>
			<item id="11" parent-id="10" level="2">Giraffes</item>
			<item id="12" parent-id="10" level="2">Pandas</item>
		</main-tree>
		</nested-cats>

	Possible Usage:

	<ul>
		<xsl:apply-templates select="nested-cats/item[@level = 1]"/>
	</ul>

	<xsl:template match="nested-cats/item">
		<xsl:variable name="id" select="@id"/>
		<li>
			<a href="{$root}/test/{@handle}"><xsl:value-of select="."/></a>
			<xsl:if test="/data/nested-cats/item[@parent-id = $id]">
				<ul>
					<xsl:apply-templates select="/data/nested-cats/item[@parent-id = $id]"/>
				</ul>
			</xsl:if>
		</li>
	</xsl:template>
';
		}

		function about(){

			return array(
				"name" => "Nested Cats",
				"description" => "Nested Cats",
				"author" => array("name" => "Andrey Lubinov",
					"email" => "andrey.lubinov@gmail.com"),
					"version" => "2.0",
				"release-date" => "2010-03-04",
			);
		}

		function grab(){

			include_once(EXTENSIONS . '/nestedcats/extension.driver.php');
			$driver = $this->_Parent->ExtensionManager->create('nestedcats');
			$xml = new XMLElement('nested-cats');
			if(!$data = $driver->fetch(0)) return $xml->appendChild(new XMLElement('error', 'No data received.'));

			$main_tree = new XMLElement('main-tree');
			foreach($data as $c) {
				$item = new XMLElement('item', $c['title']);
				$item->setAttribute('id', $c['id']);
				$item->setAttribute('parent-id', $c['parent']);
				$item->setAttribute('level', $c['level']);
				$main_tree->appendChild($item);
			}
			$xml->appendChild($main_tree);
			return $xml;
		}
	}

?>