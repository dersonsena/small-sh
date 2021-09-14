<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* layouts/base.html.twig */
class __TwigTemplate_2032093781a9d862abeb319fe3521d85adc4b0277769e8e2f1bee7247cfdebf6 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'styles' => [$this, 'block_styles'],
            'headScripts' => [$this, 'block_headScripts'],
            'title' => [$this, 'block_title'],
            'content' => [$this, 'block_content'],
            'scripts' => [$this, 'block_scripts'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"pt-br\">
    <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\">
        <meta name=\"format-detection\" content=\"telephone=no\">
        <meta http-equiv=\"Content-Language\" content=\"pt-br\">

        <!-- Css -->
        <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We\" crossorigin=\"anonymous\">
        <link rel=\"stylesheet\" href=\"https://pro.fontawesome.com/releases/v5.10.0/css/all.css\" integrity=\"sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p\" crossorigin=\"anonymous\"/>
        <link rel=\"stylesheet\" type=\"text/css\" href=\"/css/style.css\">
        ";
        // line 13
        $this->displayBlock('styles', $context, $blocks);
        // line 14
        echo "
        <!--[if lt IE 9]>
        <script src=\"http://html5shim.googlecode.com/svn/trunk/html5.js\"></script>
        <script src=\"http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js\"></script>
        <![endif]-->

        ";
        // line 20
        $this->displayBlock('headScripts', $context, $blocks);
        // line 21
        echo "
        <title>";
        // line 22
        $this->displayBlock('title', $context, $blocks);
        echo " | Trello BI</title>
    </head>
    <body>
        ";
        // line 25
        $this->loadTemplate("layouts/_header.html.twig", "layouts/base.html.twig", 25)->display($context);
        // line 26
        echo "        ";
        $this->loadTemplate("layouts/_messages.html.twig", "layouts/base.html.twig", 26)->display($context);
        // line 27
        echo "        <div class=\"container\">
            ";
        // line 28
        $this->displayBlock('content', $context, $blocks);
        // line 29
        echo "        </div>

        <!-- Load jQuery -->
        <script src=\"https://code.jquery.com/jquery-3.6.0.min.js\" integrity=\"sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=\" crossorigin=\"anonymous\"></script>
        <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj\" crossorigin=\"anonymous\"></script>
        ";
        // line 34
        $this->displayBlock('scripts', $context, $blocks);
        // line 35
        echo "    </body>
</html>
";
    }

    // line 13
    public function block_styles($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 20
    public function block_headScripts($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 22
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 28
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 34
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function getTemplateName()
    {
        return "layouts/base.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  126 => 34,  120 => 28,  114 => 22,  108 => 20,  102 => 13,  96 => 35,  94 => 34,  87 => 29,  85 => 28,  82 => 27,  79 => 26,  77 => 25,  71 => 22,  68 => 21,  66 => 20,  58 => 14,  56 => 13,  42 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "layouts/base.html.twig", "/usr/src/app/templates/layouts/base.html.twig");
    }
}
