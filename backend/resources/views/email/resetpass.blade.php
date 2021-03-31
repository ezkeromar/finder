@include('email.includes.email_head')

<div class='movableContent'>
    <table cellpadding="0" cellspacing="0" border="0" align="center">
        <tr>
            <td width="100%" colspan="3" align="left" >
                <div class="contentEditableContainer contentTextEditable">
                    <div class="contentEditable" >
                        <p>
							Bonjour {{ $name }},<br>
							Nous avons reçu une demande de réinitialisation de votre mot de passe IMZII!<br>
							Pour modifier votre mot de passe Imzii, cliquez ici ou collez le lien suivant dans votre navigateur:<br><br/>
						</p>
						<p>
							<a href="{{$base_url}}/auth/forgotpassword/{{$link}}/{{$email}}" target="_blank" style="color:rgb(255,192,0);">{{$base_url}}/auth/forgotpassword/{{$link}}/{{$email}}</a></span>
						<br><br/>
						</p>
						<p>
							Ce lien expirera dans 24 heures, assurez-vous de l’utiliser bientôt.<br>
							Merci d’utiliser Imzii et à très vite!<br><br/>
						</p>
						<p>
							L’équipe Imzii
						</p>
                    </div>
                </div>
            </td>
        </tr>
        
    </table>
</div>

@include('email.includes.email_footer')