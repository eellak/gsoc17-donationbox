var style = document.getElementById("custom_stlye").value;

if ( style )
{
    var linkElement = document.createElement("link");

    linkElement.rel = "stylesheet";
    linkElement.href = style ;

    document.head.appendChild(linkElement);

    alert(style);
}