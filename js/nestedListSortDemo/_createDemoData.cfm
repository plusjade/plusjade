<!---Before using this file, create two tables in your database with the following fields:

Table: demoSortItems

itemID (integer - primary key/autoincrement)
itemType (varchar 10)
itemTitle (varchar 100)
itemLink (varchar 500)

Table: demoSortLayout

layoutId (integer - primary key/autoincrement)
itemId (integer)
parentId (integer)
layoutOrder (integer)

--->


<cfset autoCounter= 1>
<!---The following lists provide the default data for the demo.  Feel free to add your own data, just make sure that the itemNames and itemLinks lists contain the same number of items--->
<cfset itemNames= "CNN,Sports Illustrated,Slashdot,Digg,ColdFusionJedi,Digital Backcountry,Dzone,ColdFusion Community,Ben Forta's Blog,An Architect's View,Charlie Arehart's Blog,InsideRIA,ColdFusion Zone,Kinky Solutions Blog,The No-Dans Club,Brian Kotek's Blog,Matt Woodward's Blog,Fusebox.org,jQuery,jQuery UI,CFQuickDocs,Engadget,Gizmodo,TWiT,Dilbert">
<cfset itemLinks="http://www.cnn.com,http://www.cnnsi.com,http://www.slashdot.org,http://www.digg.com,http://www.coldfusionjedi.com,http://blog.digitalbackcountry.com,http://www.dzone.com,http://www.coldfusioncommunity.org,http://www.forta.com/,http://corfield.org/blog/index.cfm,http://carehart.org/blog/client/,http://www.insideria.com,http://coldfusion.dzone.com/,http://www.bennadel.com/blog/,http://www.nodans.com/,http://www.briankotek.com/blog/,http://www.mattwoodward.com/blog,http://www.fusebox.org,http://www.jquery.com,http://ui.jquery.com,http://www.cfquickdocs.com,http://www.engadget.com,http://gizmodo.com,http://www.twit.tv,http://www.dilbert.com/">
<cfset folderNames= "Blogs,Check Daily,Check Weekly,News,Technology,Resources">

<cfloop index="i" from="1" to="#ListLen(itemNames)#">
	<cfquery datasource="#application.ds#" username="#application.username#" password="#application.password#">
		insert into demoSortItems (
			itemType,
			itemTitle,
			itemLink
		) VALUES (
			<cfqueryparam value="link" cfsqltype="cf_sql_varchar">,
			<cfqueryparam value="#ListGetAt(itemNames,i)#" cfsqltype="cf_sql_varchar">,
			<cfqueryparam value="#ListGetAt(itemLinks,i)#" cfsqltype="cf_sql_varchar">
		)
	</cfquery>
	
	<cfquery datasource="#application.ds#" username="#application.username#" password="#application.password#">
		insert into demoSortLayout (
			itemId,
			parentId,
			layoutOrder
		) VALUES (
			<cfqueryparam value="#autoCounter#" cfsqltype="cf_sql_numeric">,
			<cfqueryparam value="0" cfsqltype="cf_sql_numeric">,
			<cfqueryparam value="#autoCounter#" cfsqltype="cf_sql_numeric">
		)
	</cfquery>
	
	<cfset autoCounter= autoCounter+1>
</cfloop>
 
<cfloop index="j" from="1" to="#ListLen(folderNames)#">
	<cfquery datasource="#application.ds#" username="#application.username#" password="#application.password#">
		insert into demoSortItems (
			itemType,
			itemTitle
		) VALUES (
			<cfqueryparam value="folder" cfsqltype="cf_sql_varchar">,
			<cfqueryparam value="#ListGetAt(folderNames,j)#" cfsqltype="cf_sql_varchar">
			)
	</cfquery>
	
	<cfquery datasource="#application.ds#" username="#application.username#" password="#application.password#">
		insert into demoSortLayout (
			itemId,
			parentId,
			layoutOrder
		) VALUES (
			<cfqueryparam value="#autoCounter#" cfsqltype="cf_sql_numeric">,
			<cfqueryparam value="0" cfsqltype="cf_sql_numeric">,
			<cfqueryparam value="#autoCounter#" cfsqltype="cf_sql_numeric">
		)
	</cfquery>
	
	<cfset autoCounter= autoCounter+1>
</cfloop>

Demo data created!

