<?php class FluidCache_Standalone_template_file_Default_b90375e30db75103ca0972cffaa7e41c49585260 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
	<div class="mainContent content">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments15 = array();
$arguments15['value'] = NULL;
$renderChildrenClosure16 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'content.main', $renderingContext);
};
$viewHelper17 = $self->getViewHelper('$viewHelper17', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper17->setArguments($arguments15);
$viewHelper17->setRenderingContext($renderingContext);
$viewHelper17->setRenderChildrenClosure($renderChildrenClosure16);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output11 .= $viewHelper17->initializeArgumentsAndRender();

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
$output18 = '';
$output19 = '';

$output19 .= '
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments20 = array();
$arguments20['path'] = 'js/base.js';
$arguments20['package'] = 'Gc.Tilleuls';
$arguments20['resource'] = NULL;
$arguments20['localize'] = true;
$renderChildrenClosure21 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper22 = $self->getViewHelper('$viewHelper22', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper22->setArguments($arguments20);
$viewHelper22->setRenderingContext($renderingContext);
$viewHelper22->setRenderChildrenClosure($renderChildrenClosure21);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output19 .= $viewHelper22->initializeArgumentsAndRender();

$output19 .= '" type="text/javascript"></script>
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments23 = array();
$arguments23['path'] = 'js/main.js';
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

$output19 .= $viewHelper25->initializeArgumentsAndRender();

$output19 .= '" type="text/javascript"></script>
	<script type=\'text/javascript\' id="__bs_script__">//';

$output18 .= $output19;

$output18 .= '
	document.write("<script async src=\'//HOST:3000/browser-sync/browser-sync-client.2.4.0.js\'><\\/script>".replace(/HOST/g, location.hostname).replace(/PORT/g, location.port));
	//';

$output18 .= '</script>
';

return $output18;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output26 = '';

$output26 .= '<!DOCTYPE html>


<html>
<head>
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments27 = array();
$arguments27['name'] = 'stylesheets';
$renderChildrenClosure28 = function() use ($renderingContext, $self) {
$output29 = '';

$output29 .= '
		<link rel="stylesheet" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments30 = array();
$arguments30['path'] = 'css/main.css';
$arguments30['package'] = 'Gc.Tilleuls';
$arguments30['resource'] = NULL;
$arguments30['localize'] = true;
$renderChildrenClosure31 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper32 = $self->getViewHelper('$viewHelper32', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper32->setArguments($arguments30);
$viewHelper32->setRenderingContext($renderingContext);
$viewHelper32->setRenderChildrenClosure($renderChildrenClosure31);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output29 .= $viewHelper32->initializeArgumentsAndRender();

$output29 .= '" media="all" />
	';
return $output29;
};

$output26 .= '';

$output26 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments33 = array();
$arguments33['name'] = 'headScripts';
$renderChildrenClosure34 = function() use ($renderingContext, $self) {
$output35 = '';

$output35 .= '
		<!-- Put your scripts inclusions for the head here, they will be included in your website by TypoScript -->
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments36 = array();
$arguments36['path'] = 'js/components/modernizr.custom.js';
$arguments36['package'] = 'Gc.Tilleuls';
$arguments36['resource'] = NULL;
$arguments36['localize'] = true;
$renderChildrenClosure37 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper38 = $self->getViewHelper('$viewHelper38', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper38->setArguments($arguments36);
$viewHelper38->setRenderingContext($renderingContext);
$viewHelper38->setRenderChildrenClosure($renderChildrenClosure37);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output35 .= $viewHelper38->initializeArgumentsAndRender();

$output35 .= '" type="text/javascript"></script>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments39 = array();
$arguments39['path'] = 'js/components/jquery.min.js';
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

$output35 .= $viewHelper41->initializeArgumentsAndRender();

$output35 .= '" type="text/javascript"></script>
	';
return $output35;
};

$output26 .= '';

$output26 .= '
</head>
<body>
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments42 = array();
$arguments42['name'] = 'body';
$renderChildrenClosure43 = function() use ($renderingContext, $self) {
$output44 = '';

$output44 .= '
	<nav class="menu">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments45 = array();
$arguments45['value'] = NULL;
$renderChildrenClosure46 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'parts.menu', $renderingContext);
};
$viewHelper47 = $self->getViewHelper('$viewHelper47', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper47->setArguments($arguments45);
$viewHelper47->setRenderingContext($renderingContext);
$viewHelper47->setRenderChildrenClosure($renderChildrenClosure46);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output44 .= $viewHelper47->initializeArgumentsAndRender();

$output44 .= '
	</nav>
	<div class="mainContent content">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments48 = array();
$arguments48['value'] = NULL;
$renderChildrenClosure49 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'content.main', $renderingContext);
};
$viewHelper50 = $self->getViewHelper('$viewHelper50', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper50->setArguments($arguments48);
$viewHelper50->setRenderingContext($renderingContext);
$viewHelper50->setRenderChildrenClosure($renderChildrenClosure49);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output44 .= $viewHelper50->initializeArgumentsAndRender();

$output44 .= '
	</div>
';
return $output44;
};

$output26 .= '';

$output26 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments51 = array();
$arguments51['name'] = 'bodyScripts';
$renderChildrenClosure52 = function() use ($renderingContext, $self) {
$output53 = '';
$output54 = '';

$output54 .= '
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments55 = array();
$arguments55['path'] = 'js/base.js';
$arguments55['package'] = 'Gc.Tilleuls';
$arguments55['resource'] = NULL;
$arguments55['localize'] = true;
$renderChildrenClosure56 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper57 = $self->getViewHelper('$viewHelper57', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper57->setArguments($arguments55);
$viewHelper57->setRenderingContext($renderingContext);
$viewHelper57->setRenderChildrenClosure($renderChildrenClosure56);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output54 .= $viewHelper57->initializeArgumentsAndRender();

$output54 .= '" type="text/javascript"></script>
	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments58 = array();
$arguments58['path'] = 'js/main.js';
$arguments58['package'] = 'Gc.Tilleuls';
$arguments58['resource'] = NULL;
$arguments58['localize'] = true;
$renderChildrenClosure59 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper60 = $self->getViewHelper('$viewHelper60', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper60->setArguments($arguments58);
$viewHelper60->setRenderingContext($renderingContext);
$viewHelper60->setRenderChildrenClosure($renderChildrenClosure59);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output54 .= $viewHelper60->initializeArgumentsAndRender();

$output54 .= '" type="text/javascript"></script>
	<script type=\'text/javascript\' id="__bs_script__">//';

$output53 .= $output54;

$output53 .= '
	document.write("<script async src=\'//HOST:3000/browser-sync/browser-sync-client.2.4.0.js\'><\\/script>".replace(/HOST/g, location.hostname).replace(/PORT/g, location.port));
	//';

$output53 .= '</script>
';
return $output53;
};

$output26 .= '';

$output26 .= '
</body>
</html>
';

return $output26;
}


}
#0             15313     