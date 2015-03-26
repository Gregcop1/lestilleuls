<?php class FluidCache_Standalone_template_file_Default_4f8dec8bc7e6bd9a01f084c769e86708a9771570 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

public function getVariableContainer() {
	// TODO
	return new \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer();
}
public function getLayoutName(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;

return NULL;
}
public function hasLayout() {
return FALSE;
}

/**
 * section stylesheets
 */
public function section_8b563540d8e2cf00c7aab74292484dfc45d600e7(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '
		<link rel="stylesheet" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments1 = array();
$arguments1['path'] = 'css/main.css';
$arguments1['package'] = 'Gc.Tilleuls';
$arguments1['resource'] = NULL;
$arguments1['localize'] = true;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper3 = $self->getViewHelper('$viewHelper3', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper3->setArguments($arguments1);
$viewHelper3->setRenderingContext($renderingContext);
$viewHelper3->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output0 .= $viewHelper3->initializeArgumentsAndRender();

$output0 .= '" media="all" />
	';

return $output0;
}
/**
 * section headScripts
 */
public function section_b614b45fc3daabe190ca47a686a6154517c3cbf5(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output4 = '';

$output4 .= '
		<!-- Put your scripts inclusions for the head here, they will be included in your website by TypoScript -->
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments5 = array();
$arguments5['path'] = 'js/components/modernizr.custom.js';
$arguments5['package'] = 'Gc.Tilleuls';
$arguments5['resource'] = NULL;
$arguments5['localize'] = true;
$renderChildrenClosure6 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper7 = $self->getViewHelper('$viewHelper7', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper7->setArguments($arguments5);
$viewHelper7->setRenderingContext($renderingContext);
$viewHelper7->setRenderChildrenClosure($renderChildrenClosure6);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output4 .= $viewHelper7->initializeArgumentsAndRender();

$output4 .= '" type="text/javascript"></script>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments8 = array();
$arguments8['path'] = 'js/components/jquery.min.js';
$arguments8['package'] = 'Gc.Tilleuls';
$arguments8['resource'] = NULL;
$arguments8['localize'] = true;
$renderChildrenClosure9 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper10 = $self->getViewHelper('$viewHelper10', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper10->setArguments($arguments8);
$viewHelper10->setRenderingContext($renderingContext);
$viewHelper10->setRenderChildrenClosure($renderChildrenClosure9);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output4 .= $viewHelper10->initializeArgumentsAndRender();

$output4 .= '" type="text/javascript"></script>
	';

return $output4;
}
/**
 * section body
 */
public function section_02083f4579e08a612425c0c1a17ee47add783b94(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output11 = '';

$output11 .= '
	<nav class="menu">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments12 = array();
$arguments12['value'] = NULL;
$renderChildrenClosure13 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.menu', $renderingContext);
};
$viewHelper14 = $self->getViewHelper('$viewHelper14', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper14->setArguments($arguments12);
$viewHelper14->setRenderingContext($renderingContext);
$viewHelper14->setRenderChildrenClosure($renderChildrenClosure13);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output11 .= $viewHelper14->initializeArgumentsAndRender();

$output11 .= '
	</nav>
	<nav class="breadcrumb">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments15 = array();
$arguments15['value'] = NULL;
$renderChildrenClosure16 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.breadcrumb', $renderingContext);
};
$viewHelper17 = $self->getViewHelper('$viewHelper17', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper17->setArguments($arguments15);
$viewHelper17->setRenderingContext($renderingContext);
$viewHelper17->setRenderChildrenClosure($renderChildrenClosure16);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output11 .= $viewHelper17->initializeArgumentsAndRender();

$output11 .= '
	</nav>
	<div class="content">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments18 = array();
$arguments18['value'] = NULL;
$renderChildrenClosure19 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'content.main', $renderingContext);
};
$viewHelper20 = $self->getViewHelper('$viewHelper20', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper20->setArguments($arguments18);
$viewHelper20->setRenderingContext($renderingContext);
$viewHelper20->setRenderChildrenClosure($renderChildrenClosure19);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output11 .= $viewHelper20->initializeArgumentsAndRender();

$output11 .= '
	</div>
';

return $output11;
}
/**
 * section bodyScripts
 */
public function section_1c1dd0596640090d96e10e9c178a187288240ef4(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output21 = '';
$output22 = '';

$output22 .= '
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments23 = array();
$arguments23['path'] = 'js/base.js';
$arguments23['package'] = 'Gc.Tilleuls';
$arguments23['resource'] = NULL;
$arguments23['localize'] = true;
$renderChildrenClosure24 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper25 = $self->getViewHelper('$viewHelper25', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper25->setArguments($arguments23);
$viewHelper25->setRenderingContext($renderingContext);
$viewHelper25->setRenderChildrenClosure($renderChildrenClosure24);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output22 .= $viewHelper25->initializeArgumentsAndRender();

$output22 .= '" type="text/javascript"></script>
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments26 = array();
$arguments26['path'] = 'js/main.js';
$arguments26['package'] = 'Gc.Tilleuls';
$arguments26['resource'] = NULL;
$arguments26['localize'] = true;
$renderChildrenClosure27 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper28 = $self->getViewHelper('$viewHelper28', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper28->setArguments($arguments26);
$viewHelper28->setRenderingContext($renderingContext);
$viewHelper28->setRenderChildrenClosure($renderChildrenClosure27);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output22 .= $viewHelper28->initializeArgumentsAndRender();

$output22 .= '" type="text/javascript"></script>
	<script type=\'text/javascript\' id="__bs_script__">//';

$output21 .= $output22;

$output21 .= '
	document.write("<script async src=\'//HOST:3000/browser-sync/browser-sync-client.2.4.0.js\'><\\/script>".replace(/HOST/g, location.hostname).replace(/PORT/g, location.port));
	//';

$output21 .= '</script>
';

return $output21;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output29 = '';

$output29 .= '<!DOCTYPE html>


<html>
<head>
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments30 = array();
$arguments30['name'] = 'stylesheets';
$renderChildrenClosure31 = function() use ($renderingContext, $self) {
$output32 = '';

$output32 .= '
		<link rel="stylesheet" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments33 = array();
$arguments33['path'] = 'css/main.css';
$arguments33['package'] = 'Gc.Tilleuls';
$arguments33['resource'] = NULL;
$arguments33['localize'] = true;
$renderChildrenClosure34 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper35 = $self->getViewHelper('$viewHelper35', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper35->setArguments($arguments33);
$viewHelper35->setRenderingContext($renderingContext);
$viewHelper35->setRenderChildrenClosure($renderChildrenClosure34);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output32 .= $viewHelper35->initializeArgumentsAndRender();

$output32 .= '" media="all" />
	';
return $output32;
};

$output29 .= '';

$output29 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments36 = array();
$arguments36['name'] = 'headScripts';
$renderChildrenClosure37 = function() use ($renderingContext, $self) {
$output38 = '';

$output38 .= '
		<!-- Put your scripts inclusions for the head here, they will be included in your website by TypoScript -->
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments39 = array();
$arguments39['path'] = 'js/components/modernizr.custom.js';
$arguments39['package'] = 'Gc.Tilleuls';
$arguments39['resource'] = NULL;
$arguments39['localize'] = true;
$renderChildrenClosure40 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper41 = $self->getViewHelper('$viewHelper41', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper41->setArguments($arguments39);
$viewHelper41->setRenderingContext($renderingContext);
$viewHelper41->setRenderChildrenClosure($renderChildrenClosure40);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output38 .= $viewHelper41->initializeArgumentsAndRender();

$output38 .= '" type="text/javascript"></script>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments42 = array();
$arguments42['path'] = 'js/components/jquery.min.js';
$arguments42['package'] = 'Gc.Tilleuls';
$arguments42['resource'] = NULL;
$arguments42['localize'] = true;
$renderChildrenClosure43 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper44 = $self->getViewHelper('$viewHelper44', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper44->setArguments($arguments42);
$viewHelper44->setRenderingContext($renderingContext);
$viewHelper44->setRenderChildrenClosure($renderChildrenClosure43);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output38 .= $viewHelper44->initializeArgumentsAndRender();

$output38 .= '" type="text/javascript"></script>
	';
return $output38;
};

$output29 .= '';

$output29 .= '
</head>
<body>
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments45 = array();
$arguments45['name'] = 'body';
$renderChildrenClosure46 = function() use ($renderingContext, $self) {
$output47 = '';

$output47 .= '
	<nav class="menu">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments48 = array();
$arguments48['value'] = NULL;
$renderChildrenClosure49 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.menu', $renderingContext);
};
$viewHelper50 = $self->getViewHelper('$viewHelper50', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper50->setArguments($arguments48);
$viewHelper50->setRenderingContext($renderingContext);
$viewHelper50->setRenderChildrenClosure($renderChildrenClosure49);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output47 .= $viewHelper50->initializeArgumentsAndRender();

$output47 .= '
	</nav>
	<nav class="breadcrumb">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments51 = array();
$arguments51['value'] = NULL;
$renderChildrenClosure52 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.breadcrumb', $renderingContext);
};
$viewHelper53 = $self->getViewHelper('$viewHelper53', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper53->setArguments($arguments51);
$viewHelper53->setRenderingContext($renderingContext);
$viewHelper53->setRenderChildrenClosure($renderChildrenClosure52);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output47 .= $viewHelper53->initializeArgumentsAndRender();

$output47 .= '
	</nav>
	<div class="content">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments54 = array();
$arguments54['value'] = NULL;
$renderChildrenClosure55 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'content.main', $renderingContext);
};
$viewHelper56 = $self->getViewHelper('$viewHelper56', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper56->setArguments($arguments54);
$viewHelper56->setRenderingContext($renderingContext);
$viewHelper56->setRenderChildrenClosure($renderChildrenClosure55);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output47 .= $viewHelper56->initializeArgumentsAndRender();

$output47 .= '
	</div>
';
return $output47;
};

$output29 .= '';

$output29 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments57 = array();
$arguments57['name'] = 'bodyScripts';
$renderChildrenClosure58 = function() use ($renderingContext, $self) {
$output59 = '';
$output60 = '';

$output60 .= '
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments61 = array();
$arguments61['path'] = 'js/base.js';
$arguments61['package'] = 'Gc.Tilleuls';
$arguments61['resource'] = NULL;
$arguments61['localize'] = true;
$renderChildrenClosure62 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper63 = $self->getViewHelper('$viewHelper63', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper63->setArguments($arguments61);
$viewHelper63->setRenderingContext($renderingContext);
$viewHelper63->setRenderChildrenClosure($renderChildrenClosure62);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output60 .= $viewHelper63->initializeArgumentsAndRender();

$output60 .= '" type="text/javascript"></script>
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments64 = array();
$arguments64['path'] = 'js/main.js';
$arguments64['package'] = 'Gc.Tilleuls';
$arguments64['resource'] = NULL;
$arguments64['localize'] = true;
$renderChildrenClosure65 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper66 = $self->getViewHelper('$viewHelper66', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper66->setArguments($arguments64);
$viewHelper66->setRenderingContext($renderingContext);
$viewHelper66->setRenderChildrenClosure($renderChildrenClosure65);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output60 .= $viewHelper66->initializeArgumentsAndRender();

$output60 .= '" type="text/javascript"></script>
	<script type=\'text/javascript\' id="__bs_script__">//';

$output59 .= $output60;

$output59 .= '
	document.write("<script async src=\'//HOST:3000/browser-sync/browser-sync-client.2.4.0.js\'><\\/script>".replace(/HOST/g, location.hostname).replace(/PORT/g, location.port));
	//';

$output59 .= '</script>
';
return $output59;
};

$output29 .= '';

$output29 .= '
</body>
</html>
';

return $output29;
}


}
#0             16967     