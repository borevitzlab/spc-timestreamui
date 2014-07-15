<%@ Page Language="vb"  Debug="true"  %>
 
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<%@ Import Namespace="System.Web" %>
<%@ Import Namespace="System.Net" %>

<%
    Dim sServerName As String = Request.ServerVariables("SERVER_NAME")

    Dim s_bm As String = ""
    If Len(Request.QueryString("bm")) > 0 Then
        s_bm = Request.QueryString("bm")
    End If
    Dim s_station_id = "-"
    If Len(Request.QueryString("id")) > 0 Then
        s_station_id = Request.QueryString("id")
    End If
    
    
    'Dim s_configdirectory As String = ""
    'If Len(Request.QueryString("cd")) > 0 Then
    '    s_configdirectory = Request.QueryString("cd")
    'End If
    
    'It would be a security hole to have a url specify the file path
    'CLZ_ALERT eventually a parameter could specify the location of the config file - and then we could read it fromthere
    'but for now we will just hardcode the location based on a passed directory
    Dim s_dirpath As String = "data/a_user_data/" & s_station_id & "/"
    Dim s_filepath As String = s_dirpath & s_station_id & "_user_bookmarks.csv"
    'Dim a_bm() As String
    'a_bm = s_bm.Split(",")
    
    'Dim s_bookmark As String
    'For Each s_bookmark In a_bm
    '    Dim a_column
    'Next
    
    'Open or create data file on server
    Dim sRoot As String = HttpRuntime.AppDomainAppPath()
    Dim s_fullpath As String = sRoot & "/" & s_filepath
    Dim s_fullpath_dir As String = sRoot & "/" & s_dirpath
    
    'Set ID of new Bookmark
    
    Dim i_new_id As Integer
    If File.Exists(s_fullpath) = False Then
        i_new_id = 73
    Else
        'Open file to determine the ID of the last row
        'Inefficient - but probably good enough!
        Dim sr As StreamReader = New StreamReader(s_fullpath)
        Dim s_temp As String = "0"
        Do While sr.Peek() >= 0
            s_temp = sr.ReadLine()
        Loop
        sr.Close()
        Dim s_last_bookmark As String = s_temp
        'Get id
        Dim s_last_id As String = s_last_bookmark.Substring(0, s_last_bookmark.IndexOf(","))
        s_last_id.Trim("")
        s_last_id.Trim(" ")
        Dim i_last_id As Integer = s_last_id
        i_new_id = i_last_id + 1
    End If

    'Add ID to front of the row:
    s_bm = i_new_id & "," & s_bm
    
    
    If Not Directory.Exists(s_fullpath_dir) Then
        Directory.CreateDirectory(s_fullpath_dir)
    End If
    
    ' Create file and header row if it does not exist.
    If File.Exists(s_fullpath) = False Then
        ' Create a file to write to.
        Dim sHeader As String = "Bookmark_ID, Name, DateStart, DateEnd, DateActive, Author, DateCreated, Description, Category, Keywords, GraphState" + Environment.NewLine
        File.WriteAllText(s_fullpath, sHeader)
    End If
    
    File.AppendAllText(s_fullpath, s_bm & Environment.NewLine)

    Dim s_link_url As String = Request.Path
    Dim s_server_name As String = Request.ServerVariables("SERVER_NAME")
    s_link_url = "http://" & s_server_name & "/station.aspx?id=" & s_station_id & "&ubm=" & i_new_id
%>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Create User Bookmark: Entrada Virtual Laboratory</title>
	
	<style type="text/css" media="screen">
		@import url(../site/style.css);
		@import url(../site/style_tabs.css);
	</style>
</head>

<body>
<div class="headerband2">
	<table width="100%" cellpadding="0" cellspacing="0" style="background-image : url('../images/header_background.jpg');
	background-repeat : repeat-x;"><tr>
		<td><img src="../images/header_logo.jpg" width="327" height="30" border="0" alt=""/></td>
		<td width="100%">&nbsp;</td>
		<td></td>
		</tr>
	</table>
</div>

<div id="content" style="padding:0px 20px 20px 20px">
<h1>Create User Bookmark: Entrada Virtual Laboratory</h1>

<h2>Bookmark Created</h2>

Link to your customized graph:<br /><br />
<b><%=s_link_url %></b></br>
<br />



</div>
</body>
</html>