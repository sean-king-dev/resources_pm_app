<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #include file="upload.asp" -->
<%

'NOTE - YOU MUST HAVE VBSCRIPT v5.0 INSTALLED ON YOUR WEB SERVER
'	   FOR THIS LIBRARY TO FUNCTION CORRECTLY. YOU CAN OBTAIN IT
'	   FREE FROM MICROSOFT WHEN YOU INSTALL INTERNET EXPLORER 5.0
'	   OR LATER.


' Create the FileUploader
Dim Uploader, File
Set Uploader = New FileUploader

' This starts the upload process
Uploader.Upload()

'******************************************
' Use [FileUploader object].Form to access 
' additional form variables submitted with
' the file upload(s). (used below)
'******************************************
'Response.Write "<b>Thank you for your upload " & Uploader.Form("fullname") & "</b><br>"

' Check if any files were uploaded
If Uploader.Files.Count = 0 Then
	Response.Write "File(s) not uploaded."
Else
	' Loop through the uploaded files
	For Each File In Uploader.Files.Items
		
		'!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		'!!!!!!!!!!!!! DISABLED FOR OBVIOUS REASONS !!!!!!!!!!!!!!!!!!!!!
		'!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		'' Check where the user wants to save the file
		'If Uploader.Form("saveto") = "disk" Then
	 '
		'	' Save the file
		'	File.SaveToDisk "E:\UploadedFiles\"
	 '
		'ElseIf Uploader.Form("saveto") = "database" Then
		'	
		'	' Open the table you are saving the file to
		'	Set RS = Server.CreateObject("ADODB.Recordset")
		'	RS.Open "MyUploadTable", "CONNECT STRING OR ADO.Connection", 2, 2
		'	RS.AddNew ' create a new record
		'	
		'	RS("filename")    = File.FileName
		'	RS("filesize")	  = File.FileSize
		'	RS("contenttype") = File.ContentType
	 '	
		'	' Save the file to the database
		'	File.SaveToDatabase RS("filedata")
		'	
		'	' Commit the changes and close
		'	RS.Update
		'	RS.Close
		'End If
		'!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		'!!!!!!!!!!!!! DISABLED FOR OBVIOUS REASONS !!!!!!!!!!!!!!!!!!!!!
		'!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		
		' Output the file details to the browser
		Response.Write "<strong>" & File.FileName & "</strong><br/>"
		Response.Write "" & File.FileSize & " bytes, <em>" & File.ContentType & "</em><br/><br/>"
	
	Next
End If

%>
