.phpajaxcalendar_wrapper
{

}

.phpajaxcalendar_wrapper table#calendar
{
	margin:0 auto;
	background:#eee;
	width:60%;
	order-collapse:collapse;
}

.phpajaxcalendar_wrapper table#calendar tr td
{
	ertical-align:top;
}

.phpajaxcalendar_wrapper table#calendar tr
{
	text-align: center;
}

.phpajaxcalendar_wrapper table#calendar .month
{
    width:auto;/* stupid IE */
}

.phpajaxcalendar_wrapper table#calendar tr.daynames td
{
	width: 50px;
	background: #666;
	color: #fff;
}

.phpajaxcalendar_wrapper table#calendar tr.week td
{
	eight:50px;
	idth:30px;
	background:#fff;
	border:1px solid #ccc;
	text-align:left;
}

.phpajaxcalendar_wrapper table#calendar td.today
{
	font-weight: bold !important;
	background: lightblue !important;
}

.phpajaxcalendar_wrapper table#calendar td.has_events
{
	background: #ffffcc !important;
}



.phpajaxcalendar_wrapper table#calendar td div.event_count
{
	text-align:center;
}

.phpajaxcalendar_wrapper table#calendar td a.day_link_simple,
.phpajaxcalendar_wrapper table#calendar td div.day_simple
{
	display:block;
	text-align:center;
	padding:10px;
}

.phpajaxcalendar_wrapper table#calendar td a.day_link_simple:hover,
.phpajaxcalendar_wrapper table#calendar td a.selected
{
	background:orange;
}

/* ajax load div*/
#calendar_event_details{
	margin-top:10px;
	padding-top:10px;
}

.calendar_item{
	padding:10px; 
	border:1px solid #ccc; 
	margin:10px
}