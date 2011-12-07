// Angie Radtke November 2011

window.addEvent('domready', function() {
	
	allelements=($$(".mobile"));


	for(i=0; i<=allelements.length;i++)
		{	
		what='div#menuwrapper'+[i];
		var div = new Element('div',{
		'class': 'mobile_select',
		html: '<h2><a href="#" id="menuopener'+i+'"  onclick="openmenu(\'menuwrapper'+i+'\');return false" aria-controls="menuwrapper'+i+'"><span>Openmenu</span></a></h2>',

		}).inject(allelements[i],'before');
	
	
       elid='menuopener'+i;
		if(document.id('menuwrapper'+[i])==undefined)
		{
			var mySecondElement = new Element('div#menuwrapper'+[i],{'class':'mobilewrap'}).wraps(allelements[i]);
			mySecondElement.setProperty('role', 'menubar');
			mySecondElement.setProperty('aria-labelledby', elid);
		}
		
		size=document.body.getSize();
		if (size.x<=482)
			{
		mySecondElement.style.display='none';
		}
		
		}

	
});


window.addEvent('resize', function() {

	size=document.body.getSize();

	if (size.x>=482)
		{    if($$('.mobilewrap'))
				{
	
			
			$$('.mobilewrap').setStyle('display','block');
			$$('.mobilewrap').setStyle('opacity','1');
			
				}
			
		}
	if (size.x<=481)
		{ 
		$$('.mobilewrap').setStyle('opacity','0');
		$$('.mobilewrap').setStyle('display','none');
	
		}
});

function openmenu(what)
{
	var state=document.id(what).getStyle('display');

	if(state=='none')
		{
		document.id(what).style.display='block';
		document.id(what).style.opacity='1';
		document.id(what).setProperty('aria-expanded', 'true');
		document.id(what).setProperty('aria-hidden', 'false');
		}
	else {
		document.id(what).style.opacity='0';
		document.id(what).style.display='none';
	 
	
		document.id(what).setProperty('aria-expanded', 'false');
		document.id(what).setProperty('aria-hidden', 'true');
	}
		

}