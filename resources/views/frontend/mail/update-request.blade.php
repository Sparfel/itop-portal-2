<html>
<head>
    <STYLE TYPE="text/css">
        BODY
        {
            color:black;
            background-color:white;
            font:normal 12px Verdana,Times New Roman, Times, serif;
        }
        A:link{color:#646496}
        A:visited{color:#646496}
        A:hover{color:#646496}

    </STYLE>
</head>
<body>
<p>Bonjour</p>
<p>Le ticket <b>{{ $updRequest['ref'] }}</b> <i>{{$updRequest['title']}}</i> a été mis à jour à partir du portail par
    <a href="mailto:{{$updRequest['caller_email']}}?Subject={{ $updRequest['ref'] }} - {{$updRequest['title']}}">{{$updRequest['caller']}}</a> et concerne <b>{{$updRequest['organization']}}</b>.</p>
<div style="background-color:#eeeeee;border:1px solid #cccccc;padding:5px 10px">{!! $updRequest['message'] !!}</div>
<p>Pour de plus amples informations cliquez ici <a href="{{ $updRequest['link'] }}"> {{ $updRequest['ref'] }}</a>.</p>


<style>
    .footer_msg {
        color:black;
        font-size:7.5pt;
        font-family:"Arial","sans-serif","Times New Roman";
    }
    .emptyline {
        color:black;
        font-size:12.0pt;
        font-family:"Arial","Times New Roman","serif";
    }
    .identity {
        color:black;
        font-size:10.0pt;
        font-family:"Arial","sans-serif";
    }
    .text {
        color:black;
        font-size:11.0pt;
        font-family:"Calibri","sans-serif";
    }
</style>
<p class="text">Cordialement.</p>
<p>&nbsp;</p>
<hr /><p><img src="https://portal.debian/img/itop-logo-square.png" style="height:50px;"/></p>
</body>
</html>
