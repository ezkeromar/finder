@include('email.includes.email_head')

<div class='movableContent'>
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600">
        <tr>
            <td width="100%" colspan="3" align="center" style="padding-bottom:10px;padding-top:25px;">
                <div class="contentEditableContainer contentTextEditable">
                    <div class="contentEditable" >
                        <h2 >Message reçu</h2>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="100">&nbsp;</td>
            <td width="400" align="center" style="padding-bottom:5px;">
                <div class="contentEditableContainer contentTextEditable">
                    <div class="contentEditable" >
                        <p>
                            Bonjour,
                            <br/><br/>
							L'utilisateur: <b>{{ $name }} ({{ $email }})</b> a envoyé un message : 
                            <span><b>{{ $message_text }}</b></span>
                        </p>
                    </div>
                </div>
            </td>
            <td width="100">&nbsp;</td>
        </tr>
    </table>
</div>

@include('email.includes.email_footer')