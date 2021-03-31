@include('email.includes.email_head')

<div class='movableContent'>
    <table cellpadding="0" cellspacing="0" border="0" align="center">
        <tr>
            <td width="100%" colspan="3" align="left" >
                <div class="contentEditableContainer contentTextEditable" style="padding: 20px 0;">
                    <div class="contentEditable" >
                        <p>
							Bonjour {{ $name }},<br>
							<center>
								{{$name_client}} vient de vous évaluer<span style="color:#ffc000,font-size:18px;"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
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