@include('email.includes.email_head')

<div class='movableContent'>
    <table cellpadding="0" cellspacing="0" border="0" align="center">
        <tr>
            <td width="100%" colspan="3" align="left" >
                <div class="contentEditableContainer contentTextEditable" style="padding: 20px 0;">
                    <div class="contentEditable" >
                        <p>
							Bonjour {{ $name }},<br>
							Nous tenons � vous informer que nous avons bien re�u votre message.<br>
							Nous proc�derons � l'�tude de�votre�demande et vous contacterons prochainement.
							<br><br/>
						</p>
						
						
						<p>
							Cordialement,<br>
							L��quipe Imzii
						</p>
                    </div>
                </div>
            </td>
        </tr>
        
    </table>
</div>

@include('email.includes.email_footer')