@include('email.includes.email_head')

<div class='movableContent'>
    <table cellpadding="0" cellspacing="0" border="0" align="center" style="-webkit-margin-start: 0px;-webkit-margin-end: 0px;">
        <tr>
            <td width="100%" colspan="3" align="left" >
                <div class="contentEditableContainer contentTextEditable" style="padding: 20px 0;">
                    <div class="contentEditable" >
                        <p>
							Bonjour {{ $name }},<br>
							{{$name_client}} souhaite prendre contact avec vous sur notre réseau Imzii.
							<br><br/>
							<center>
								<img src="{{ url('assets/img/profile.png') }}" style="padding-right:15px;" alt='profile'  data-default="placeholder" />
								<a href="{{$base_url}}" id="profile" style ="color: black;font-weight: bold;background-color: #ffae00;padding: 7px 40px;text-decoration: none;border-radius: 5px;"><span>Voir le profil</span></a>
							</center>
							<br><br/>
						</p>
						
						<p>
							Cordialement,<br>
							L’équipe Imzii
						</p>
                    </div>
                </div>
            </td>
        </tr>
        
    </table>
</div>

@include('email.includes.email_footer')