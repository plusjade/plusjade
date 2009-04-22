
<cfcomponent output="false">
	<cfset this.name = "nestedListSort">
	<cfset this.clientManagement = true>

<cffunction name="onApplicationStart" returnType="boolean" output="true">
	<!---Set application level variables regarding the datasource--->
	<cfset application.ds= "{datasource}">
	<cfset application.username="{username}">
	<cfset application.password="{password}">
	<!---Instantiate listManager object--->
	<cfset application.listManager= CreateObject("component","{dot path to CFC}.listManager").init(application.ds,application.username,application.password)>
<cfreturn true>
</cffunction>

<cffunction name="onApplicationEnd" returnType="void" output="false">
	<cfargument name="applicationScope" required="true">
</cffunction>

<cffunction name="onSessionStart" returnType="void" output="false">
	
</cffunction>

<cffunction name="onSessionEnd" returnType="void" output="false">
	<cfargument name="sessionScope" type="struct" required="true">
	<cfargument name="applicationScope" type="struct" required="false">

</cffunction>

<cffunction name="onRequestStart" returnType="boolean" output="false">
	<cfargument name="thePage" type="string" required="true">
	<cfreturn true>
</cffunction>

<cffunction name="onRequest" returnType="void">
	<cfargument name="thePage" type="string" required="true">
	<cfsetting requesttimeout="240">
	<cfinclude template="#arguments.thePage#">
</cffunction>

<cffunction name="onRequestEnd" returnType="void" output="false">
	<cfargument name="thePage" type="string" required="true">
</cffunction>

<!---There is no onError function:  the Fusebox error plugin will handle errors that occur in the Fusebox app.  All files outside of Fusebox app will have to have their own error handling:--->

<cffunction name="onError" returnType="void" output="true">
	<cfargument name="exception" required="true">
	<cfargument name="eventname" type="string" required="true">
	<cfdump var="#arguments#"><cfabort>
	
	</cffunction>

</cfcomponent>