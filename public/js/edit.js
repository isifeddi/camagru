function enable_text(status, id)
{
	status=!status;	
	document.getElementById(id).disabled = status;
	
}