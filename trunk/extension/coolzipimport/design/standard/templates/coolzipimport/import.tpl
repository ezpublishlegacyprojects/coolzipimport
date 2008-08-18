<form enctype="multipart/form-data" method="post" action={"/coolzipimport/import"|ezurl}>

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{"Zip with images import"|i18n("extension/coolzipimport")}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

   <img align="right" src={"zip.png"|ezimage} alt="zip" />

{section show=eq($coolzipimport_mode,'imported')}

<h1>{"Document is now imported"|i18n("extension/coolzipimport")}</h1>
<ul>
 <!--  <li>{"Document imported as"|i18n("extension/coolzipimport")} <a href={$url_alias|ezurl}>{$node_name}</a>.</li> -->
   <li><a href={"/coolzipimport/import"|ezurl}>{"Import another document"|i18n("extension/coolzipimport")}</a></li>
</ul>
<br />
<br />
<br />
<br />
</div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">


{section-else}

<h1>{"Import zip with images"|i18n("extension/coolzipimport")}</h1>

<p>
{"You can import many images at once after compressing them as a zip file into eZ publish from this page. You are
asked where to place the document and eZ publish does the rest. The document is converted into
the appropriate class during the import, you get a notice about this after the import is done. Please upload the zip:"|i18n("extension/coolzipimport")}
</p>

<input type="hidden" name="MAX_FILE_SIZE" value="100000000"/>
<input class="box" name="coolzipimport_file" type="file" />

</div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">

<input class="button" type="submit" name="StoreButton" value="{'Upload file'|i18n('design/standard/coolzipimport/import')}" />


{/section}

</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</form>