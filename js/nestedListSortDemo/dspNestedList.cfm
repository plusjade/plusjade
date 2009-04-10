<html>
<head>
	<title>Nested List Sort Demo</title>
	<!---Custom CSS file--->
	<link href="nestedListSort.css" type="text/css" rel="stylesheet">
	<!---Required jQuery files--->
	<script type="text/javascript" src="jquery-1.2.1.js"></script>
	<script type="text/javascript" src="jquery.ui-1.0/jquery.dimensions.js"></script>
	<script type="text/javascript" src="jquery.ui-1.0/ui.mouse.js"></script>
	<script type="text/javascript" src="jquery.ui-1.0/ui.draggable.js"></script>
	<script type="text/javascript" src="jquery.ui-1.0/ui.droppable.js"></script>
	<script type="text/javascript" src="jquery.ui-1.0/ui.sortable.js"></script>
	<!---Custom JavaScript file that assigns the JavaScript events for this page--->
	<script type="text/javascript" src="nestedListSort.js"></script>
</head>
<body> 

<!---Generate the HTML code for the main list and its sublists.  In a true application, this code should be separated from the display page--->	
<cfset theList= application.listManager.outputNestedList()>

<h2>Nested List Sort Demo</h2>

<p>This is a demonstration of how you can use the <a href="http://ui.jquery.com" target="_blank">jQuery UI</a> Sortables code to rearrange list items and nested lists within a main list and then record those changes back to a database.</p>

<p>Click and drag on any hyperlink or heading in the list below to organize the bookmarks, then click on the <strong>Save Changes</strong> button.</p> 

<p>One thing to note:  to rearrange the order of sibling nested lists, you need to move the lists up rather than down, because if you move them one list below another it will become a sublist.</p>

<h3>Bookmarks</h3>
<cfoutput>
	#theList#
</cfoutput>

<form name="nestedListForm" id="nestedListForm" method="post" action="actSaveListChanges.cfm">
	<!---If you need to debug the JavaScript function, set the input type to "text" and uncomment the JavaScript function in nestedListSort.js that will prevent the form submission from occurring--->
	<input type="hidden" name="listResults" id="listResults" value="">
	<input type="submit" name="listSubmit" id="listSubmit" value="Save Changes">
</form>

</body>
</html>