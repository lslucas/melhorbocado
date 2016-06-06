// JavaScript Document

var searchGlobal = false;
var interval;

//break button
//=============================================================
function disableselect(e)
{
	return false;
}

function reEnable()
{
	return true;
}

//if IE4+   
document.onselectstart = new Function("return false");
document.oncontextmenu = new Function("return false");

//if NS6
if (window.sidebar)
{
	document.onmousedown = disableselect;
	document.onclick = reEnable;
}   
//=============================================================

//==============================================================================================

function verificaCEP(cep)
{
	$('<iframe />', {
		name: 	'requestCEP',
		id:   	'requestCEP',
		width: 	'0%',
		height: '0%',
		border: '0',
		src:  	'/cep/botao.php?cep=' + cep
	}).appendTo('#requestCEP');
}

//==============================================================================================

function addVarS()
{
	searchGlobal = true;
}

//==============================================================================================

function delVarS()
{
	searchGlobal = false;
	//searchHide();
}

//==============================================================================================

function searchHide()
{
	if(!searchGlobal)
	{
		$("#search").animate({height:0 }, 350, function() { $("#search").css("display","none"); });
	}
}

//==============================================================================================

function CloseSala()
{
	$(document).ready(function()
	{
		$('body').css("overflow-y","auto");
		$('body').css("overflow-x","auto");
		$('body').attr("scroll","auto");
		
		$('#sda').html("");
		$('#sda').css("display", "none");
		$('#fsda').css("display", "none");
		
		window.location.reload();
	});
	
}

//==============================================================================================

function CursosDestaque()
{
	$('#destaque').attr('src','slider.php');
}

$(document).ready(function() { CursosDestaque(); });

//==============================================================================================

function OpenSala(url, name)
{
	$(document).ready(function()
	{
		//title navegador
		document.title = name + " | Cursos Online | Faça na Prática";
		
		$('body').css("overflow-y","hidden");
		$('body').css("overflow-x","hidden");
		$('body').attr("scroll","no");
		
		$('#sda').html('<center><span id="tempContent" style="display:block; margin-top:200px; font-size:11px; color:#fff;">CARREGANDO SALA DE AULA<br /><img src="imagens/image_479827.gif" id="loadSala" /></span></center>');
		
		$('<iframe />', {
			name: 	'SalaDeAula',
			id:   	'SalaDeAula',
			width: 	'100%',
			height: $(window).height(),
			border: '0',
			src:  url
		}).appendTo('#sda');
		
		$('#sda').css("display", "block");
		$('#fsda').css("display", "block");
		$('#SalaDeAula').css("display", "none");
		
		setTimeout(function() 
		{ 
			$('#loadSala').remove(); 
			$('#tempContent').remove();
			$('#SalaDeAula').css("display", "block"); 
		}, 3000);
	});
}

//==============================================================================================

function searchNow(input)
{
	$(document).ready(function()
	{
		var aParams = input.value;
		var stype 	= "post";
		var surl 	= "instruction.php";
		var params  = "info=search&p=" + aParams;
		
		if($("#search").css("display") == "none")
		{
			$("#search").css("display","block");
			
			if(window.location.href.indexOf("/index.php") > -1)
			{
				$("#search").animate({height: ($(window).height() - 136) }, 350);
			}
			else
			{
				$("#search").animate({height: ($(window).height() - 175) }, 350);
			}
		}

		//$("#search").height($("#center-inter").height() - 95);
		//$('#search').alternateScroll();

		$.ajax
		(
			{
				type: stype.toUpperCase(),
				url: surl,
				data: params,
				success: function(data)
				{
					if(data)
					{
						$("#search-result").attr('accept-charset','ISO-8859-1');
						$("#search-result").html(data);
						$('#search').alternateScroll();
					}
				}
			}
		);
	});
}

//==============================================================================================

function ajustaMenuCursos()
{
	$(document).ready(function()
	{
		$("#cursos").height($(window).height() - 136);
		$('#cursos').alternateScroll();
	});
}

//==============================================================================================

function openCursosOnline()
{
	$("#openCursosOnline").click
	(
		function()
		{
			switch($("#marginCenter").css("display"))
			{
				case("none"):
					$("#marginCenter").css("display","block");
					
					$("#maskBack").css("display","block");
					$("#maskBack").animate({width: "200px"}, 500);
					
					$("#cursos").css("display","block");
					$("#cursos").animate({width: "200px"}, 500);
					
					$("#textMenuCursos").css("display","block");
					$("#textMenuCursos").animate({width: "200px"}, 500);
				break;
				
				case("block"):
					$("#textMenuCursos").animate({width: "0px"}, 450);
					
					$("#maskBack").animate({width: "0px"}, 500, function()
					{ 
						//$("#categoriaCursos").css("display","none"); 
						$("#marginCenter").css("display","none"); 
						$("#maskBack").css("display","none"); 
					});
	
					$("#cursos").animate({width: "0px"}, 500, function ()
					{ 
						$("#cursos").css("display","none");
					});
				break;
			}
		}
	);
}

$(document).ready(function() { openCursosOnline(); } );

//==============================================================================================

function doPrint(element, v)
{	
	$(document).ready(function() 
	{
		if(v) { element = "#" + element; } else { element = "." + element; }
				
		$(element.toString()).printElement();
	});
}

//==============================================================================================

function sPrint()
{	
	$('.conteudo').printElement();
}

function sPrintCT()
{	
	$('#ictf').printElement();
}

//==============================================================================================

function lkp(frm)
{
	var form = document.getElementById(frm);
	
	form.target = "_self";
	form.method = "post";
	form.action = "https://pagseguro.uol.com.br/v2/checkout/payment.html";
	$("#" + frm).attr('accept-charset','ISO-8859-1');
	form.submit();
}

//==============================================================================================

function closeConstAlert()
{
	$(document).ready(function() 
	{
		$("#consAlert").css("display", "none");
		$("#consAlertText").css("display", "none");
	});
	
}

//==============================================================================================

function forms()
{
	$(document).ready(function() 
	{
		$("#printCtOver").click(
			function()
			{
				doPrint($(this).attr("tagprint"), true);
			}
		);
	});

	//-----------------------------------------------------------------
			
	$(document).ready(function() 
	{
		$("#frmContato").submit(
			function()
			{
				$(this).attr("method", "post");
				$(this).attr("action", "instruction.php?info=mail");
			}
		);
		
		//-----------------------------------------------------------------
		
		$("#frmCadastro").submit(
			function()
			{
				if(validateForm(document.frmCadastro))
				{
					$(this).attr("method", "post");
					$(this).attr("action", "instruction.php?info=save.user");
				}
				else
				{
					return false;
				}
			}
		);

		//-----------------------------------------------------------------
	
		$("#frmContinueCadastro").submit(
			function()
			{
				if(validateFormNotRegister(document.frmContinueCadastro))
				{
					$(this).attr("method", "post");
					$(this).attr("action", "/cadastro-de-aluno");
				}
				else
				{
					return false;
				}
			}
		);

		//-----------------------------------------------------------------
		
		$("#frmNewsLetterSend").submit(
			function()
			{
				var frm = document.frmNewsLetterSend;
				if(frm.i_newsLetter.value.length == 0) { frm.i_newsLetter.focus(); return false; }
				if(!ValidaEmail(frm.i_newsLetter.value)) { frm.i_newsLetter.focus(); return false; }
				
				$(this).attr("method", "post");
				$(this).attr("action", "instruction.php?info=save.newsletter");
			}
		);

		//-----------------------------------------------------------------
		
		$("#frmContatoTop").submit(
			function()
			{
				if(validateFormDefaultWithChars(document.frmContatoTop))
				{
					$("#form_home").css("display", "none");
					$("#enviado").css("display", "block");
					
					$(this).attr("method", "post");
					$(this).attr("target", "formContato");
					$(this).attr("action", "instruction.php?info=mail");
					
					setTimeout("showFormIndex();", 5000);
					
					$(this).submit();
					
					return false;
				}
				else
				{
					return false;
				}
			}
		);
		
	});
}

forms();

//==============================================================================================

function showFormIndex()
{
	var f = document.frmContatoTop;
	
	f.nome.value = "nome";
	f.email.value = "e-mail";
	f.telefone.value = "telefone";
	f.mensagem.value = "mensagem";
	
	document.getElementById("enviado").style.display = "none";
	document.getElementById("form_home").style.display = "block";
}

//==============================================================================================

function visibleForm()
{
	$(document).ready(function() 
	{
		$("#form_home").css("display", "block");
		$("#enviado").css("display", "none");
		
		$("#frmContatoTop").find("input").each(
			
			function()
			{
				if(this.name == "nome") { this.value = "nome"; }
			}
											   
		);
	});
}

//==============================================================================================

function ctf(id)
{
	$(document).ready(function() 
	{
		if($("#overflowCerti").css("display") == "none")
		{
			$("#overflowCerti").css("display", "block");
			$("#ctf").css("display", "block");
			
			$("#ictf").attr("src", "ctf.php?id=" + id);
			
		}
		else
		{
			$("#overflowCerti").css("display", "none");
			$("#ctf").css("display", "none");
		}
	});
}

//==============================================================================================

function setDisplay()
{
	document.getElementById("screen-aviso").style.display = "none";
	document.getElementById("aviso").style.display = "none";
	document.getElementById("aviso-ok").style.display = "none";
}

//==============================================================================================

function GetModulo(cursoID, get)
{
	window.location.href = "?crsid=" + cursoID + "&get=" + get;
}

//==============================================================================================

function ProximoModulo(cursoID, prxM)
{
	window.location.href = "?crsid=" + cursoID + "&prxM=" + prxM;
}

//==============================================================================================

function ModuloAnterior(cursoID, antM)
{
	window.location.href = "?crsid=" + cursoID + "&antM=" + antM;
}

//==============================================================================================

function ExecSC(softwareID, ac)
{
	var f = document.frmPagSeguro;
	f.submit();
	return false;
	
	window.location.href = "instruction.php?info=shopping_cart&si=" + softwareID + "&isS=" + ac;
}

//==============================================================================================

function openLogin()
{
	$(document).ready(function() 
	{
		if($("#login").css("display") == "none")
		{
			$("#login").css("display", "block");
			$("#menu-studio").css("background", "#339966");
		}
		else
		{
			$("#login").css("display", "none");
			$("#menu-studio").css("background", "none");
		}
	});
}


//==============================================================================================

function FillUname(value)
{
	document.frmCadastro.uname.value = value;

	$(document).ready
	(
		function()
		{
			var aParams = value;
			var stype 	= "get";
			var surl 	= "instruction.php";
			var params  = "info=verify.users&u=" + aParams;
			
			$.ajax
			(
				{
					type: stype.toUpperCase(),
					url: surl,
					data: params,
					success: function(data)
					{
						$("#textAlertLogin").html(data);
					}
				}
			);
		}
	);
	
	//Usuário já cadastrado!
}

//==============================================================================================

function validateForm(frm)
{
	if(frm.nome.value.length == 0) { frm.nome.focus(); return false; }

	if(frm.cep.value.length == 0) { frm.cep.focus(); return false; }	
	
	if(frm.endereco.value.length == 0) { frm.endereco.focus(); return false; }
	
	if(frm.numero_casa.value.length == 0) { frm.numero_casa.focus(); return false; }
	if(frm.complemento.value.length == 0) { frm.complemento.focus(); return false; }
	
	if(frm.bairro.value.length == 0) { frm.bairro.focus(); return false; }
	if(frm.cidade.value.length == 0) { frm.cidade.focus(); return false; }

	if(frm.email.value.length == 0) { frm.email.focus(); return false; }
	if(!ValidaEmail(frm.email.value)) { frm.email.focus(); return false; }
	
	if(frm.uname.value.length == 0) { frm.uname.focus(); return false; }
	if(frm.upass.value.length == 0) { frm.upass.focus(); return false; }
	if(frm.upass_confirm.value.length == 0) { frm.upass_confirm.focus(); return false; }
	
	if(frm.upass.value != frm.upass_confirm.value)
	{ 
		frm.upass.focus(); 
		document.getElementById("textAlertPass").innerHTML = "As senhas não coincidem!";
		return false; 
	}
	else
	{
		document.getElementById("textAlertPass").innerHTML = "";
	}
	
	if(document.getElementById("textAlertLogin").innerHTML == "Usuário já cadastrado!")
	{
		return false;
	}
	
	return true;
}

//==============================================================================================

function validateFormNotRegister(frm)
{
	if(frm.nome.value.length == 0) { frm.nome.focus(); return false; }
	if(frm.email.value.length == 0) { frm.email.focus(); return false; }
	if(!ValidaEmail(frm.email.value)) { frm.email.focus(); return false; }
	
	return true;
}

//==============================================================================================

function validateFormDefaultWithChars(frm)
{
	if(frm.nome.value == "nome") { frm.nome.focus(); return false; }
	if(frm.email.value == "e-mail") { frm.email.focus(); return false; }
	//if(frm.ddd.value == "ddd") { frm.ddd.focus(); return false; }
	if(frm.telefone.value == "telefone") { frm.telefone.focus(); return false; }
	if(frm.mensagem.value == "mensagem") { frm.mensagem.focus(); return false; }

	if(frm.nome.value.length == 0) { frm.nome.focus(); return false; }
	if(frm.email.value.length == 0) { frm.email.focus(); return false; }
	if(!ValidaEmail(frm.email.value)) { frm.email.focus(); return false; }
	//if(frm.ddd.value.length == 0) { frm.ddd.focus(); return false; }
	if(frm.telefone.value.length == 0) { frm.telefone.focus(); return false; }
	if(frm.mensagem.value.length == 0) { frm.mensagem.focus(); return false; }
	
	return true;
}
				
//==============================================================================================

function FinalizarCompra()
{
	window.location.href = "instruction.php?info=insert.curso.compra";
}

function VerificaLoginMatricula()
{
	window.location.href = "instruction.php?info=v.login.matricula&addinfo=csc";
}
//==============================================================================================

function ValidaEmail(mail)
{
	var er = new RegExp(/^[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]{2,}\.[A-Za-z0-9]{2,}(\.[A-Za-z0-9])?/);
	
	if(typeof(mail) == "string")
	{
		if(er.test(mail)){ return true; }
	}
	
	else if(typeof(mail) == "object")
	{
		if(er.test(mail.value))
		{
			return true; 
		}
	}
	else
	{
		return false;
	}					
	
}

//==============================================================================================

function validateLogin(frm)
{
	if(frm.uname.value.length == 0) { frm.uname.focus(); return false; }
	if(frm.upass.value.length == 0) { frm.upass.focus(); return false; }
}

//==============================================================================================

function ProgressPagSeguro()
{
	var frm = document.frmPagSeguro;
	$("#frmPagSeguro").attr('target','_top');
	$("#frmPagSeguro").attr('accept-charset','ISO-8859-1');
	frm.submit();
}

//==============================================================================================

function setVisible(p)
{
	p = document.getElementById(p);
	
	if(p.style.display == "none")
	{
		p.style.display = "block";
	}
	else if(p.style.display == "")
	{
		p.style.display = "block";
	}
	else
	{
		p.style.display = "none";
	}
}

//==============================================================================================

$(document).ready(function(){
						   
	$('#menu-centro-inter a').click(function()
	{
		if(this.id != "")
		{
			$.scrollTo(this.id, 2000, {easing:'jswing'});
			// return false;
		}
		return false;
	});

});

//==============================================================================================

$(document).ready(function()
{
	$(window).scroll(function()
	{
		if($(window).scrollTop() >= 190)
		{
			$("#menuTopChange").fadeIn();
		}
		else
		{
			if($(window).scrollTop() < 180)
			{
				$("#menuTopChange").fadeOut();
			}
		}
	});				
});						

//==============================================================================================

function getURL(url)
{
	window.location.href = url;
}

//==============================================================================================

/*
function enviar(f)
{
	if(f.nome.value.length == 0) { f.nome.focus(); return false; }
	if(f.nome.value == "nome") { f.nome.focus(); return false; }
	
	if(f.email.value.length == 0) { f.email.focus(); return false; }
	if(f.email.value == "e-mail") { f.email.focus(); return false; }
	
	if(f.telefone.value.length == 0) { f.telefone.focus(); return false; }
	if(f.telefone.value == "telefone") { f.telefone.focus(); return false; }
		
	if(f.msg.value.length == 0) { f.msg.focus(); return false; }
	if(f.msg.value == "mensagem") { f.msg.focus(); return false; }
	
	send();
	return true;
}
				
//==============================================================================================

function send()
{
	document.getElementById("enviado").style.display = "block";
	setTimeout("hide();", 5000);
}
				
//==============================================================================================

function hide()
{
	var f = document.contato;
	
	f.nome.value = "nome";
	f.email.value = "e-mail";
	f.telefone.value = "telefone";
	f.msg.value = "mensagem";
	
	document.getElementById("enviado").style.display = "none";
}
*/
//==============================================================================================
