<html>
<head>
	<title>Nested List Sort Demo</title>
	<!---Custom CSS file--->
	<link href="nestedListSort.css" type="text/css" rel="stylesheet">
</head>
<body>
<h2>Nested List Sort Demo</h2>
<!---Update the database records based on the string in form.listResults.  The function will return false if an error occurred.--->
<cfset temp= application.listManager.saveListChanges(Trim(form.listResults))>

<cfif temp EQ true>
	<p class="success_text">The changes to the list have been saved.</p>
	
	<p>To verify this, <a href="dspNestedList.cfm">click here to reload the page that displays the list</a></p>
<cfelse>
	<p class="error_text">An error occurred during the saving process.  Please use the Back button of your web browser to go back and try again.</p>
</cfif>
</body>
</html>