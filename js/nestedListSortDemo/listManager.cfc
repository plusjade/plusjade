<cfcomponent displayname="listManager" hint="I contain the functions for both generating the list for display and saving the outcome." output="false">
	<cfproperty name="ds" displayname="ds" hint="The name of the datasource.  Value set during init." type="string" />
	<cfproperty name="username" displayname="username" hint="The username for accessing the datasource.  Value set during init." type="string" />
	<cfproperty name="password" displayname="password" hint="TThe password for accessing the datasource.  Value set during init." type="string" />
	
	<cfset variables.ds= "" />
	<cfset variables.username= "" />
	<cfset variables.password= "" />
	
	<cffunction name="init" output="false" returntype="listManager" access="public" hint="I initialize the object">
		<cfargument name="ds" type="string" required="true" hint="The name of the datasource" />
		<cfargument name="username" type="string" required="true" hint="The username for accessing the datasource" />
		<cfargument name="password" type="string" required="true" hint="The password for accessing the datasource" />
		<cfset variables.ds= arguments.ds />
		<cfset variables.username= arguments.username />
		<cfset variables.password= arguments.password />
		<cfreturn this />
	</cffunction>
	
	<cffunction name="retrieveListData" output="false" access="private" returntype="query" hint="I generate a query object containing all of the list data">
		<cfset var masterQry= "" />
	
		<!---Retrieve the data for all of the items in the list and their corresponding layout records--->
		<cfquery name="masterQry" datasource="#variables.ds#" username="#variables.username#" password="#variables.password#">
			select a.itemId, a.itemType, a.itemTitle, a.itemLink,
			b.parentId, b.layoutOrder
			from demoSortItems a, demoSortLayout b
			where b.itemId= a.itemId
			order by b.layoutOrder ASC
		</cfquery>	
	
		<cfreturn masterQry />
	</cffunction>
	
	<cffunction name="generateList" output="false" access="private" returntype="string" hint="I generate the nested list">
		<cfargument name="masterQry" type="query" required="true" hint="The query object containing the list data">
		<cfset var qry= "">
		<cfset var listResult= "" />
		
		<!---Retrieve from the query object all of the items that are direct decendants of the master list (whose parentId is 0)--->
		<cfquery name="qry" dbtype="query">
			select itemId, itemType, itemTitle, itemLink
			from arguments.masterQry
			where parentId= <cfqueryparam value="0" cfsqltype="cf_sql_numeric">
			order by layoutOrder ASC
		</cfquery>
		
		<!---Create the top-level list and populate it with the items from the new query object--->
		<cfsavecontent variable="listResult">
			<ul id="masterList">
				<cfoutput query="qry">
					<!---Determine if the record is an item or a container--->
					<cfif qry.itemType EQ 'link'>
						<!---If the record is a item, create a <li> element for it--->
						<li id="#qry.itemId#" class="record item">
							<a href="#qry.itemLink#" target="_blank">#qry.itemTitle#</a>
						</li>
					<cfelse>
						<!---If the record is a container, create a <li> element for it that contains an new <ul>, and pass the id of the record and the masterQry object to the createSublist function.  Make sure the <ul> element has an id that begins with "UL_" and ends with the id of the record.--->
						<li id="#qry.itemId#" class="record container"><span class="containerTitle">#qry.itemTitle#</span>
							<ul id="UL_#qry.itemId#">
								<!---Create an invisible, unmovable <li> element.  Otherwise, if a user moves all of the items out of the container, they will be unable to move items back into the container:  the container must always have at least on <li> in it--->
								<li class="unmovable"></li>
								<!---Call the createSublist function and pass the current record id and the masterQry object to it.  It will create all of the <li> elements that fall under the current container--->
								#createSublist(qry.itemId,masterQry)#
							</ul>
						</li>	
					</cfif>
				</cfoutput>
				<li class="unmovable"></li>
			</ul>
		</cfsavecontent>
		
		<cfreturn listResult />
	</cffunction>
	
	<cffunction name="createSublist" output="false" access="private" returntype="string" hint="I create the list of items for a nested list within another list">
		<cfargument name="parentId" type="numeric" required="true" hint="The id of the container record">
		<cfargument name="masterQry" type="query" required="true" hint="The query object containing all of the list data">
		<cfset var qry= "">
		<cfset var subList= "" />
		
		<!---Generate a query object containing all of the records that are children of the parent container--->
		<cfquery name="qry" dbtype="query">
			select itemId, itemType, itemTitle, itemLink
			from arguments.masterQry
			where parentId= <cfqueryparam value="#arguments.parentId#" cfsqltype="cf_sql_numeric">
			order by layoutOrder ASC
		</cfquery>
		<cfsavecontent variable="subList">
			<cfoutput query="qry">
				<!---Determine if the record is an item or a container--->
				<cfif qry.itemType EQ 'link'>
					<!---If the record is a item, create a <li> element for it--->
					<li id="#qry.itemId#" class="record item">
						<a href="#qry.itemLink#" target="_blank">#qry.itemTitle#</a>
					</li>
				<cfelse>
					<!---If the record is a container, create a <li> element for it that contains an new <ul>, and pass the id of the record and the masterQry object to the createSublist function.  Make sure the <ul> element has an id that begins with "UL_" and ends with the id of the record.--->
					<li id="#qry.itemId#" class="record container" ><span class="containerTitle">#qry.itemTitle#</span>
						<ul id="UL_#qry.itemId#">
							<!---Create an invisible, unmovable <li> element.  Otherwise, if a user moves all of the items out of the container, they will be unable to move items back into the container:  the container must always have at least on <li> in it--->
							<li class="unmovable"></li>
							<!---Call the createSublist function and pass the current record id and the masterQry object to it.  It will create all of the <li> elements that fall under the current container--->
							#createSublist(qry.itemId,masterQry)#
						</ul>
					</li>	
				</cfif>
			</cfoutput>
		</cfsavecontent>
		
	<cfreturn subList />
	</cffunction>
	
	<cffunction name="outputNestedList" output="false" access="public" returntype="string" hint="I return the nested list">
		<cfset var masterQry= "" />
		<cfset var listOutput= "" />
		
		<!---Get all of the data needed for the list--->
		<cfset masterQry= retrieveListData()>
		
		<!---Pass the query object to the main function that controls the process of creating the HTML for the entire list--->
		<cfset listOutput= generateList(masterQry)>
						
		<cfreturn listOutput />
	</cffunction>
	
	
	<cffunction name="saveListChanges" output="false" access="public" returntype="boolean" hint="I save the changes to the list">
		<cfargument name="listResult" type="string" required="yes" hint="The string containing the data needed to update the order and hierarchy of the list">
		<cfset var count= 1>
		<cfset var recrd= "">
		
		<cftry>
		<!---Loop through the list using the record delimiter ("^")--->
		<cfloop index="count" from="1" to="#ListLen(arguments.listResult,'^')#">
			<!---Store the current record information into the recrd variable--->
			<cfset recrd= ListGetAt(arguments.listResult,count,'^')>
			
			<!---Update the matching layout record using the data in the recrd line, which each piece of data delimited by the data delimiter ("|")--->
			<cfquery datasource="#variables.ds#" username="#variables.username#" password="#variables.password#">
				update demoSortLayout
					set parentId= <cfqueryparam value="#ListGetAt(recrd,2,'|')#" cfsqltype="cf_sql_numeric">,
					layoutOrder= <cfqueryparam value="#count#" cfsqltype="cf_sql_numeric">
				where itemId= <cfqueryparam value="#ListGetAt(recrd,1,'|')#" cfsqltype="cf_sql_numeric">
			</cfquery>	
		</cfloop>
		
		<cfreturn true />
		
		<cfcatch type="any">
			<!---If an error occurs, return false--->
			<cfreturn false />
		</cfcatch>
		</cftry>
	
	</cffunction>
	
</cfcomponent>