<?php class FluidCache_TYPO3_Neos_Backend_Module_action_index_73a1cff215b17d663e47de12f8fcb31a44de65a2 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

public function getVariableContainer() {
	// TODO
	return new \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer();
}
public function getLayoutName(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;

return 'Default';
}
public function hasLayout() {
return TRUE;
}

/**
 * section head
 */
public function section_1a954628a960aaef81d7b2d4521929579f3541e6(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '
	<title>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments1 = array();
$arguments1['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'title', $renderingContext);
$arguments1['keepQuotes'] = false;
$arguments1['encoding'] = 'UTF-8';
$arguments1['doubleEncode'] = true;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
return NULL;
};
$value3 = ($arguments1['value'] !== NULL ? $arguments1['value'] : $renderChildrenClosure2());

$output0 .= !is_string($value3) && !(is_object($value3) && method_exists($value3, '__toString')) ? $value3 : htmlspecialchars($value3, ($arguments1['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments1['encoding'], $arguments1['doubleEncode']);

$output0 .= '</title>

	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments4 = array();
// Rendering Boolean node
$arguments4['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleConfiguration.additionalResources.styleSheets', $renderingContext));
$arguments4['then'] = NULL;
$arguments4['else'] = NULL;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
$output6 = '';

$output6 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments7 = array();
$arguments7['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleConfiguration.additionalResources.styleSheets', $renderingContext);
$arguments7['as'] = 'additionalResource';
$arguments7['key'] = '';
$arguments7['reverse'] = false;
$arguments7['iteration'] = NULL;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
$output9 = '';

$output9 .= '
			<link rel="stylesheet" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments10 = array();
$arguments10['path'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'additionalResource', $renderingContext);
$arguments10['package'] = NULL;
$arguments10['resource'] = NULL;
$arguments10['localize'] = true;
$renderChildrenClosure11 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper12 = $self->getViewHelper('$viewHelper12', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper12->setArguments($arguments10);
$viewHelper12->setRenderingContext($renderingContext);
$viewHelper12->setRenderChildrenClosure($renderChildrenClosure11);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output9 .= $viewHelper12->initializeArgumentsAndRender();

$output9 .= '" />
		';
return $output9;
};

$output6 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments7, $renderChildrenClosure8, $renderingContext);

$output6 .= '
	';
return $output6;
};
$viewHelper13 = $self->getViewHelper('$viewHelper13', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper13->setArguments($arguments4);
$viewHelper13->setRenderingContext($renderingContext);
$viewHelper13->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output0 .= $viewHelper13->initializeArgumentsAndRender();

$output0 .= '

	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments14 = array();
$arguments14['path'] = 'Library/jquery/jquery-2.0.3.js';
$arguments14['package'] = NULL;
$arguments14['resource'] = NULL;
$arguments14['localize'] = true;
$renderChildrenClosure15 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper16 = $self->getViewHelper('$viewHelper16', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper16->setArguments($arguments14);
$viewHelper16->setRenderingContext($renderingContext);
$viewHelper16->setRenderChildrenClosure($renderChildrenClosure15);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output0 .= $viewHelper16->initializeArgumentsAndRender();

$output0 .= '"></script>
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments17 = array();
// Rendering Boolean node
$arguments17['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleConfiguration.additionalResources.javaScripts', $renderingContext));
$arguments17['then'] = NULL;
$arguments17['else'] = NULL;
$renderChildrenClosure18 = function() use ($renderingContext, $self) {
$output19 = '';

$output19 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments20 = array();
$arguments20['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleConfiguration.additionalResources.javaScripts', $renderingContext);
$arguments20['as'] = 'additionalResource';
$arguments20['key'] = '';
$arguments20['reverse'] = false;
$arguments20['iteration'] = NULL;
$renderChildrenClosure21 = function() use ($renderingContext, $self) {
$output22 = '';

$output22 .= '
			<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments23 = array();
$arguments23['path'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'additionalResource', $renderingContext);
$arguments23['package'] = NULL;
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

$output22 .= '"></script>
		';
return $output22;
};

$output19 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments20, $renderChildrenClosure21, $renderingContext);

$output19 .= '
	';
return $output19;
};
$viewHelper26 = $self->getViewHelper('$viewHelper26', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper26->setArguments($arguments17);
$viewHelper26->setRenderingContext($renderingContext);
$viewHelper26->setRenderChildrenClosure($renderChildrenClosure18);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output0 .= $viewHelper26->initializeArgumentsAndRender();

$output0 .= '

	<script type="text/javascript">
		// TODO: Get rid of those global variables
		';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\JavascriptConfigurationViewHelper
$arguments27 = array();
$renderChildrenClosure28 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper29 = $self->getViewHelper('$viewHelper29', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\JavascriptConfigurationViewHelper');
$viewHelper29->setArguments($arguments27);
$viewHelper29->setRenderingContext($renderingContext);
$viewHelper29->setRenderChildrenClosure($renderChildrenClosure28);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\JavascriptConfigurationViewHelper

$output0 .= $viewHelper29->initializeArgumentsAndRender();

$output0 .= '
	</script>

	<link rel="neos-menudata" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments30 = array();
$arguments30['action'] = 'index';
$arguments30['controller'] = 'Backend\\Menu';
$arguments30['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments30['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array31 = array();
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$arguments32 = array();
$renderChildrenClosure33 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper34 = $self->getViewHelper('$viewHelper34', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper');
$viewHelper34->setArguments($arguments32);
$viewHelper34->setRenderingContext($renderingContext);
$viewHelper34->setRenderChildrenClosure($renderChildrenClosure33);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$array31['version'] = $viewHelper34->initializeArgumentsAndRender();
$arguments30['arguments'] = $array31;
$arguments30['subpackage'] = NULL;
$arguments30['section'] = '';
$arguments30['format'] = '';
$arguments30['additionalParams'] = array (
);
$arguments30['addQueryString'] = false;
$arguments30['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments30['useParentRequest'] = false;
$renderChildrenClosure35 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper36 = $self->getViewHelper('$viewHelper36', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper36->setArguments($arguments30);
$viewHelper36->setRenderingContext($renderingContext);
$viewHelper36->setRenderChildrenClosure($renderChildrenClosure35);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output0 .= $viewHelper36->initializeArgumentsAndRender();

$output0 .= '" />
	<!-- TODO: Make sure the schema information and edit / preview panel data isn\'t necessary in backend modules -->
	<link rel="neos-vieschema" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments37 = array();
$arguments37['action'] = 'vieSchema';
$arguments37['controller'] = 'Backend\\Schema';
$arguments37['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments37['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array38 = array();
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$arguments39 = array();
$renderChildrenClosure40 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper41 = $self->getViewHelper('$viewHelper41', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper');
$viewHelper41->setArguments($arguments39);
$viewHelper41->setRenderingContext($renderingContext);
$viewHelper41->setRenderChildrenClosure($renderChildrenClosure40);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$array38['version'] = $viewHelper41->initializeArgumentsAndRender();
$arguments37['arguments'] = $array38;
$arguments37['subpackage'] = NULL;
$arguments37['section'] = '';
$arguments37['format'] = '';
$arguments37['additionalParams'] = array (
);
$arguments37['addQueryString'] = false;
$arguments37['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments37['useParentRequest'] = false;
$renderChildrenClosure42 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper43 = $self->getViewHelper('$viewHelper43', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper43->setArguments($arguments37);
$viewHelper43->setRenderingContext($renderingContext);
$viewHelper43->setRenderChildrenClosure($renderChildrenClosure42);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output0 .= $viewHelper43->initializeArgumentsAndRender();

$output0 .= '" />
	<link rel="neos-nodetypeschema" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments44 = array();
$arguments44['action'] = 'nodeTypeSchema';
$arguments44['controller'] = 'Backend\\Schema';
$arguments44['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments44['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array45 = array();
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$arguments46 = array();
$renderChildrenClosure47 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper48 = $self->getViewHelper('$viewHelper48', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper');
$viewHelper48->setArguments($arguments46);
$viewHelper48->setRenderingContext($renderingContext);
$viewHelper48->setRenderChildrenClosure($renderChildrenClosure47);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$array45['version'] = $viewHelper48->initializeArgumentsAndRender();
$arguments44['arguments'] = $array45;
$arguments44['subpackage'] = NULL;
$arguments44['section'] = '';
$arguments44['format'] = '';
$arguments44['additionalParams'] = array (
);
$arguments44['addQueryString'] = false;
$arguments44['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments44['useParentRequest'] = false;
$renderChildrenClosure49 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper50 = $self->getViewHelper('$viewHelper50', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper50->setArguments($arguments44);
$viewHelper50->setRenderingContext($renderingContext);
$viewHelper50->setRenderChildrenClosure($renderChildrenClosure49);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output0 .= $viewHelper50->initializeArgumentsAndRender();

$output0 .= '" />
	<link rel="neos-editpreviewdata" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments51 = array();
$arguments51['action'] = 'editPreview';
$arguments51['controller'] = 'Backend\\Settings';
$arguments51['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments51['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array52 = array();
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$arguments53 = array();
$renderChildrenClosure54 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper55 = $self->getViewHelper('$viewHelper55', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper');
$viewHelper55->setArguments($arguments53);
$viewHelper55->setRenderingContext($renderingContext);
$viewHelper55->setRenderChildrenClosure($renderChildrenClosure54);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$array52['version'] = $viewHelper55->initializeArgumentsAndRender();
$arguments51['arguments'] = $array52;
$arguments51['subpackage'] = NULL;
$arguments51['section'] = '';
$arguments51['format'] = '';
$arguments51['additionalParams'] = array (
);
$arguments51['addQueryString'] = false;
$arguments51['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments51['useParentRequest'] = false;
$renderChildrenClosure56 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper57 = $self->getViewHelper('$viewHelper57', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper57->setArguments($arguments51);
$viewHelper57->setRenderingContext($renderingContext);
$viewHelper57->setRenderChildrenClosure($renderChildrenClosure56);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output0 .= $viewHelper57->initializeArgumentsAndRender();

$output0 .= '" />

	<link rel="stylesheet" type="text/css" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments58 = array();
$output59 = '';

$output59 .= 'Styles/Includes';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments60 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper
$arguments61 = array();
$renderChildrenClosure62 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper63 = $self->getViewHelper('$viewHelper63', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper');
$viewHelper63->setArguments($arguments61);
$viewHelper63->setRenderingContext($renderingContext);
$viewHelper63->setRenderChildrenClosure($renderChildrenClosure62);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper
$arguments60['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean($viewHelper63->initializeArgumentsAndRender());
$arguments60['then'] = '-built';
$arguments60['else'] = NULL;
$renderChildrenClosure64 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper65 = $self->getViewHelper('$viewHelper65', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper65->setArguments($arguments60);
$viewHelper65->setRenderingContext($renderingContext);
$viewHelper65->setRenderChildrenClosure($renderChildrenClosure64);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output59 .= $viewHelper65->initializeArgumentsAndRender();

$output59 .= '.css';
$arguments58['path'] = $output59;
$arguments58['package'] = 'TYPO3.Neos';
$arguments58['resource'] = NULL;
$arguments58['localize'] = true;
$renderChildrenClosure66 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper67 = $self->getViewHelper('$viewHelper67', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper67->setArguments($arguments58);
$viewHelper67->setRenderingContext($renderingContext);
$viewHelper67->setRenderChildrenClosure($renderChildrenClosure66);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output0 .= $viewHelper67->initializeArgumentsAndRender();

$output0 .= '" />
';

return $output0;
}
/**
 * section body
 */
public function section_02083f4579e08a612425c0c1a17ee47add783b94(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output68 = '';

$output68 .= '
	<body class="neos neos-module neos-controls neos-module-';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments69 = array();
$arguments69['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleClass', $renderingContext);
$arguments69['keepQuotes'] = false;
$arguments69['encoding'] = 'UTF-8';
$arguments69['doubleEncode'] = true;
$renderChildrenClosure70 = function() use ($renderingContext, $self) {
return NULL;
};
$value71 = ($arguments69['value'] !== NULL ? $arguments69['value'] : $renderChildrenClosure70());

$output68 .= !is_string($value71) && !(is_object($value71) && method_exists($value71, '__toString')) ? $value71 : htmlspecialchars($value71, ($arguments69['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments69['encoding'], $arguments69['doubleEncode']);

$output68 .= '">
		<div class="neos-module-wrap">
			<ul class="neos-breadcrumb">
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments72 = array();
$arguments72['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleBreadcrumb', $renderingContext);
$arguments72['key'] = 'path';
$arguments72['as'] = 'configuration';
$arguments72['iteration'] = 'iterator';
$arguments72['reverse'] = false;
$renderChildrenClosure73 = function() use ($renderingContext, $self) {
$output74 = '';

$output74 .= '
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments75 = array();
// Rendering Boolean node
$arguments75['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configuration.hideInMenu', $renderingContext));
$arguments75['then'] = NULL;
$arguments75['else'] = NULL;
$renderChildrenClosure76 = function() use ($renderingContext, $self) {
$output77 = '';

$output77 .= '
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments78 = array();
$renderChildrenClosure79 = function() use ($renderingContext, $self) {
$output80 = '';

$output80 .= '
							<li>
								';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper
$arguments81 = array();
$arguments81['path'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'path', $renderingContext);
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments82 = array();
// Rendering Boolean node
$arguments82['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'iterator.isLast', $renderingContext));
$arguments82['then'] = 'active';
$arguments82['else'] = NULL;
$renderChildrenClosure83 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper84 = $self->getViewHelper('$viewHelper84', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper84->setArguments($arguments82);
$viewHelper84->setRenderingContext($renderingContext);
$viewHelper84->setRenderChildrenClosure($renderChildrenClosure83);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments81['class'] = $viewHelper84->initializeArgumentsAndRender();
$arguments81['additionalAttributes'] = NULL;
$arguments81['data'] = NULL;
$arguments81['action'] = NULL;
$arguments81['arguments'] = array (
);
$arguments81['section'] = '';
$arguments81['format'] = '';
$arguments81['additionalParams'] = array (
);
$arguments81['addQueryString'] = false;
$arguments81['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments81['dir'] = NULL;
$arguments81['id'] = NULL;
$arguments81['lang'] = NULL;
$arguments81['style'] = NULL;
$arguments81['title'] = NULL;
$arguments81['accesskey'] = NULL;
$arguments81['tabindex'] = NULL;
$arguments81['onclick'] = NULL;
$arguments81['name'] = NULL;
$arguments81['rel'] = NULL;
$arguments81['rev'] = NULL;
$arguments81['target'] = NULL;
$renderChildrenClosure85 = function() use ($renderingContext, $self) {
$output86 = '';

$output86 .= '<i class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments87 = array();
$arguments87['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configuration.icon', $renderingContext);
$arguments87['keepQuotes'] = false;
$arguments87['encoding'] = 'UTF-8';
$arguments87['doubleEncode'] = true;
$renderChildrenClosure88 = function() use ($renderingContext, $self) {
return NULL;
};
$value89 = ($arguments87['value'] !== NULL ? $arguments87['value'] : $renderChildrenClosure88());

$output86 .= !is_string($value89) && !(is_object($value89) && method_exists($value89, '__toString')) ? $value89 : htmlspecialchars($value89, ($arguments87['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments87['encoding'], $arguments87['doubleEncode']);

$output86 .= '"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments90 = array();
$arguments90['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configuration.label', $renderingContext);
$arguments90['keepQuotes'] = false;
$arguments90['encoding'] = 'UTF-8';
$arguments90['doubleEncode'] = true;
$renderChildrenClosure91 = function() use ($renderingContext, $self) {
return NULL;
};
$value92 = ($arguments90['value'] !== NULL ? $arguments90['value'] : $renderChildrenClosure91());

$output86 .= !is_string($value92) && !(is_object($value92) && method_exists($value92, '__toString')) ? $value92 : htmlspecialchars($value92, ($arguments90['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments90['encoding'], $arguments90['doubleEncode']);
return $output86;
};
$viewHelper93 = $self->getViewHelper('$viewHelper93', $renderingContext, 'TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper');
$viewHelper93->setArguments($arguments81);
$viewHelper93->setRenderingContext($renderingContext);
$viewHelper93->setRenderChildrenClosure($renderChildrenClosure85);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper

$output80 .= $viewHelper93->initializeArgumentsAndRender();

$output80 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments94 = array();
// Rendering Boolean node
$arguments94['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'iterator.isLast', $renderingContext));
$arguments94['then'] = NULL;
$arguments94['else'] = NULL;
$renderChildrenClosure95 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments96 = array();
$renderChildrenClosure97 = function() use ($renderingContext, $self) {
return '<span class="neos-divider">/</span>';
};
$viewHelper98 = $self->getViewHelper('$viewHelper98', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper98->setArguments($arguments96);
$viewHelper98->setRenderingContext($renderingContext);
$viewHelper98->setRenderChildrenClosure($renderChildrenClosure97);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
return $viewHelper98->initializeArgumentsAndRender();
};
$arguments94['__elseClosure'] = function() use ($renderingContext, $self) {
return '<span class="neos-divider">/</span>';
};
$viewHelper99 = $self->getViewHelper('$viewHelper99', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper99->setArguments($arguments94);
$viewHelper99->setRenderingContext($renderingContext);
$viewHelper99->setRenderChildrenClosure($renderChildrenClosure95);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output80 .= $viewHelper99->initializeArgumentsAndRender();

$output80 .= '
							</li>
						';
return $output80;
};
$viewHelper100 = $self->getViewHelper('$viewHelper100', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper100->setArguments($arguments78);
$viewHelper100->setRenderingContext($renderingContext);
$viewHelper100->setRenderChildrenClosure($renderChildrenClosure79);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output77 .= $viewHelper100->initializeArgumentsAndRender();

$output77 .= '
					';
return $output77;
};
$arguments75['__elseClosure'] = function() use ($renderingContext, $self) {
$output101 = '';

$output101 .= '
							<li>
								';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper
$arguments102 = array();
$arguments102['path'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'path', $renderingContext);
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments103 = array();
// Rendering Boolean node
$arguments103['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'iterator.isLast', $renderingContext));
$arguments103['then'] = 'active';
$arguments103['else'] = NULL;
$renderChildrenClosure104 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper105 = $self->getViewHelper('$viewHelper105', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper105->setArguments($arguments103);
$viewHelper105->setRenderingContext($renderingContext);
$viewHelper105->setRenderChildrenClosure($renderChildrenClosure104);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments102['class'] = $viewHelper105->initializeArgumentsAndRender();
$arguments102['additionalAttributes'] = NULL;
$arguments102['data'] = NULL;
$arguments102['action'] = NULL;
$arguments102['arguments'] = array (
);
$arguments102['section'] = '';
$arguments102['format'] = '';
$arguments102['additionalParams'] = array (
);
$arguments102['addQueryString'] = false;
$arguments102['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments102['dir'] = NULL;
$arguments102['id'] = NULL;
$arguments102['lang'] = NULL;
$arguments102['style'] = NULL;
$arguments102['title'] = NULL;
$arguments102['accesskey'] = NULL;
$arguments102['tabindex'] = NULL;
$arguments102['onclick'] = NULL;
$arguments102['name'] = NULL;
$arguments102['rel'] = NULL;
$arguments102['rev'] = NULL;
$arguments102['target'] = NULL;
$renderChildrenClosure106 = function() use ($renderingContext, $self) {
$output107 = '';

$output107 .= '<i class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments108 = array();
$arguments108['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configuration.icon', $renderingContext);
$arguments108['keepQuotes'] = false;
$arguments108['encoding'] = 'UTF-8';
$arguments108['doubleEncode'] = true;
$renderChildrenClosure109 = function() use ($renderingContext, $self) {
return NULL;
};
$value110 = ($arguments108['value'] !== NULL ? $arguments108['value'] : $renderChildrenClosure109());

$output107 .= !is_string($value110) && !(is_object($value110) && method_exists($value110, '__toString')) ? $value110 : htmlspecialchars($value110, ($arguments108['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments108['encoding'], $arguments108['doubleEncode']);

$output107 .= '"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments111 = array();
$arguments111['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configuration.label', $renderingContext);
$arguments111['keepQuotes'] = false;
$arguments111['encoding'] = 'UTF-8';
$arguments111['doubleEncode'] = true;
$renderChildrenClosure112 = function() use ($renderingContext, $self) {
return NULL;
};
$value113 = ($arguments111['value'] !== NULL ? $arguments111['value'] : $renderChildrenClosure112());

$output107 .= !is_string($value113) && !(is_object($value113) && method_exists($value113, '__toString')) ? $value113 : htmlspecialchars($value113, ($arguments111['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments111['encoding'], $arguments111['doubleEncode']);
return $output107;
};
$viewHelper114 = $self->getViewHelper('$viewHelper114', $renderingContext, 'TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper');
$viewHelper114->setArguments($arguments102);
$viewHelper114->setRenderingContext($renderingContext);
$viewHelper114->setRenderChildrenClosure($renderChildrenClosure106);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper

$output101 .= $viewHelper114->initializeArgumentsAndRender();

$output101 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments115 = array();
// Rendering Boolean node
$arguments115['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'iterator.isLast', $renderingContext));
$arguments115['then'] = NULL;
$arguments115['else'] = NULL;
$renderChildrenClosure116 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments117 = array();
$renderChildrenClosure118 = function() use ($renderingContext, $self) {
return '<span class="neos-divider">/</span>';
};
$viewHelper119 = $self->getViewHelper('$viewHelper119', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper119->setArguments($arguments117);
$viewHelper119->setRenderingContext($renderingContext);
$viewHelper119->setRenderChildrenClosure($renderChildrenClosure118);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
return $viewHelper119->initializeArgumentsAndRender();
};
$arguments115['__elseClosure'] = function() use ($renderingContext, $self) {
return '<span class="neos-divider">/</span>';
};
$viewHelper120 = $self->getViewHelper('$viewHelper120', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper120->setArguments($arguments115);
$viewHelper120->setRenderingContext($renderingContext);
$viewHelper120->setRenderChildrenClosure($renderChildrenClosure116);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output101 .= $viewHelper120->initializeArgumentsAndRender();

$output101 .= '
							</li>
						';
return $output101;
};
$viewHelper121 = $self->getViewHelper('$viewHelper121', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper121->setArguments($arguments75);
$viewHelper121->setRenderingContext($renderingContext);
$viewHelper121->setRenderChildrenClosure($renderChildrenClosure76);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output74 .= $viewHelper121->initializeArgumentsAndRender();

$output74 .= '
				';
return $output74;
};

$output68 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments72, $renderChildrenClosure73, $renderingContext);

$output68 .= '
			</ul>
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments122 = array();
// Rendering Boolean node
$arguments122['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleContents', $renderingContext));
$arguments122['then'] = NULL;
$arguments122['else'] = NULL;
$renderChildrenClosure123 = function() use ($renderingContext, $self) {
$output124 = '';

$output124 .= '
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments125 = array();
$arguments125['value'] = NULL;
$renderChildrenClosure126 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleContents', $renderingContext);
};
$viewHelper127 = $self->getViewHelper('$viewHelper127', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper127->setArguments($arguments125);
$viewHelper127->setRenderingContext($renderingContext);
$viewHelper127->setRenderChildrenClosure($renderChildrenClosure126);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output124 .= $viewHelper127->initializeArgumentsAndRender();

$output124 .= '
			';
return $output124;
};
$viewHelper128 = $self->getViewHelper('$viewHelper128', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper128->setArguments($arguments122);
$viewHelper128->setRenderingContext($renderingContext);
$viewHelper128->setRenderChildrenClosure($renderChildrenClosure123);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output68 .= $viewHelper128->initializeArgumentsAndRender();

$output68 .= '
			<div id="neos-application" class="neos">
				<div id="neos-top-bar">
					<div id="neos-top-bar-right">
						<div id="neos-user-actions">
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments129 = array();
$arguments129['partial'] = 'Backend/UserMenu';
$arguments129['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments129['section'] = NULL;
$arguments129['optional'] = false;
$renderChildrenClosure130 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper131 = $self->getViewHelper('$viewHelper131', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper131->setArguments($arguments129);
$viewHelper131->setRenderingContext($renderingContext);
$viewHelper131->setRenderChildrenClosure($renderChildrenClosure130);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output68 .= $viewHelper131->initializeArgumentsAndRender();

$output68 .= '
						</div>
					</div>
				</div>
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments132 = array();
$arguments132['partial'] = 'Backend/Menu';
// Rendering Array
$array133 = array();
$array133['sites'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'sites', $renderingContext);
$array133['modules'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'modules', $renderingContext);
$array133['modulePath'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleConfiguration.path', $renderingContext);
$arguments132['arguments'] = $array133;
$arguments132['section'] = NULL;
$arguments132['optional'] = false;
$renderChildrenClosure134 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper135 = $self->getViewHelper('$viewHelper135', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper135->setArguments($arguments132);
$viewHelper135->setRenderingContext($renderingContext);
$viewHelper135->setRenderChildrenClosure($renderChildrenClosure134);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output68 .= $viewHelper135->initializeArgumentsAndRender();

$output68 .= '
			</div>
		</div>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments136 = array();
$arguments136['path'] = '2/js/bootstrap.min.js';
$arguments136['package'] = 'TYPO3.Twitter.Bootstrap';
$arguments136['resource'] = NULL;
$arguments136['localize'] = true;
$renderChildrenClosure137 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper138 = $self->getViewHelper('$viewHelper138', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper138->setArguments($arguments136);
$viewHelper138->setRenderingContext($renderingContext);
$viewHelper138->setRenderChildrenClosure($renderChildrenClosure137);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output68 .= $viewHelper138->initializeArgumentsAndRender();

$output68 .= '"></script>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments139 = array();
$arguments139['path'] = 'Library/fixedsticky/fixedsticky.js';
$arguments139['package'] = 'TYPO3.Neos';
$arguments139['resource'] = NULL;
$arguments139['localize'] = true;
$renderChildrenClosure140 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper141 = $self->getViewHelper('$viewHelper141', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper141->setArguments($arguments139);
$viewHelper141->setRenderingContext($renderingContext);
$viewHelper141->setRenderChildrenClosure($renderChildrenClosure140);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output68 .= $viewHelper141->initializeArgumentsAndRender();

$output68 .= '"></script>

		<script type="text/javascript">
			(function($) {
				$(function() {
					$(\'.neos-footer\').fixedsticky();
				});
			})(jQuery);
		</script>

		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments142 = array();
$arguments142['path'] = 'Library/requirejs/require.js';
$arguments142['package'] = 'TYPO3.Neos';
$arguments142['resource'] = NULL;
$arguments142['localize'] = true;
$renderChildrenClosure143 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper144 = $self->getViewHelper('$viewHelper144', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper144->setArguments($arguments142);
$viewHelper144->setRenderingContext($renderingContext);
$viewHelper144->setRenderChildrenClosure($renderChildrenClosure143);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output68 .= $viewHelper144->initializeArgumentsAndRender();

$output68 .= '"></script>
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments145 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper
$arguments146 = array();
$renderChildrenClosure147 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper148 = $self->getViewHelper('$viewHelper148', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper');
$viewHelper148->setArguments($arguments146);
$viewHelper148->setRenderingContext($renderingContext);
$viewHelper148->setRenderChildrenClosure($renderChildrenClosure147);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper
$arguments145['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean($viewHelper148->initializeArgumentsAndRender());
$arguments145['then'] = NULL;
$arguments145['else'] = NULL;
$renderChildrenClosure149 = function() use ($renderingContext, $self) {
$output150 = '';

$output150 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments151 = array();
$renderChildrenClosure152 = function() use ($renderingContext, $self) {
$output153 = '';

$output153 .= '
				<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments154 = array();
$arguments154['path'] = 'JavaScript/ContentModule-built.js';
$arguments154['package'] = 'TYPO3.Neos';
$arguments154['resource'] = NULL;
$arguments154['localize'] = true;
$renderChildrenClosure155 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper156 = $self->getViewHelper('$viewHelper156', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper156->setArguments($arguments154);
$viewHelper156->setRenderingContext($renderingContext);
$viewHelper156->setRenderChildrenClosure($renderChildrenClosure155);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output153 .= $viewHelper156->initializeArgumentsAndRender();

$output153 .= '"></script>
			';
return $output153;
};
$viewHelper157 = $self->getViewHelper('$viewHelper157', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper157->setArguments($arguments151);
$viewHelper157->setRenderingContext($renderingContext);
$viewHelper157->setRenderChildrenClosure($renderChildrenClosure152);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output150 .= $viewHelper157->initializeArgumentsAndRender();

$output150 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments158 = array();
$renderChildrenClosure159 = function() use ($renderingContext, $self) {
$output160 = '';

$output160 .= '
				<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments161 = array();
$arguments161['path'] = 'JavaScript/ContentModuleBootstrap.js';
$arguments161['package'] = 'TYPO3.Neos';
$arguments161['resource'] = NULL;
$arguments161['localize'] = true;
$renderChildrenClosure162 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper163 = $self->getViewHelper('$viewHelper163', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper163->setArguments($arguments161);
$viewHelper163->setRenderingContext($renderingContext);
$viewHelper163->setRenderChildrenClosure($renderChildrenClosure162);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output160 .= $viewHelper163->initializeArgumentsAndRender();

$output160 .= '"></script>
			';
return $output160;
};
$viewHelper164 = $self->getViewHelper('$viewHelper164', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper164->setArguments($arguments158);
$viewHelper164->setRenderingContext($renderingContext);
$viewHelper164->setRenderChildrenClosure($renderChildrenClosure159);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output150 .= $viewHelper164->initializeArgumentsAndRender();

$output150 .= '
		';
return $output150;
};
$arguments145['__thenClosure'] = function() use ($renderingContext, $self) {
$output165 = '';

$output165 .= '
				<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments166 = array();
$arguments166['path'] = 'JavaScript/ContentModule-built.js';
$arguments166['package'] = 'TYPO3.Neos';
$arguments166['resource'] = NULL;
$arguments166['localize'] = true;
$renderChildrenClosure167 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper168 = $self->getViewHelper('$viewHelper168', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper168->setArguments($arguments166);
$viewHelper168->setRenderingContext($renderingContext);
$viewHelper168->setRenderChildrenClosure($renderChildrenClosure167);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output165 .= $viewHelper168->initializeArgumentsAndRender();

$output165 .= '"></script>
			';
return $output165;
};
$arguments145['__elseClosure'] = function() use ($renderingContext, $self) {
$output169 = '';

$output169 .= '
				<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments170 = array();
$arguments170['path'] = 'JavaScript/ContentModuleBootstrap.js';
$arguments170['package'] = 'TYPO3.Neos';
$arguments170['resource'] = NULL;
$arguments170['localize'] = true;
$renderChildrenClosure171 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper172 = $self->getViewHelper('$viewHelper172', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper172->setArguments($arguments170);
$viewHelper172->setRenderingContext($renderingContext);
$viewHelper172->setRenderChildrenClosure($renderChildrenClosure171);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output169 .= $viewHelper172->initializeArgumentsAndRender();

$output169 .= '"></script>
			';
return $output169;
};
$viewHelper173 = $self->getViewHelper('$viewHelper173', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper173->setArguments($arguments145);
$viewHelper173->setRenderingContext($renderingContext);
$viewHelper173->setRenderChildrenClosure($renderChildrenClosure149);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output68 .= $viewHelper173->initializeArgumentsAndRender();

$output68 .= '
	</body>
';

return $output68;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output174 = '';

$output174 .= '
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper
$arguments175 = array();
$arguments175['name'] = 'Default';
$renderChildrenClosure176 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper177 = $self->getViewHelper('$viewHelper177', $renderingContext, 'TYPO3\Fluid\ViewHelpers\LayoutViewHelper');
$viewHelper177->setArguments($arguments175);
$viewHelper177->setRenderingContext($renderingContext);
$viewHelper177->setRenderChildrenClosure($renderChildrenClosure176);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper

$output174 .= $viewHelper177->initializeArgumentsAndRender();

$output174 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments178 = array();
$arguments178['name'] = 'head';
$renderChildrenClosure179 = function() use ($renderingContext, $self) {
$output180 = '';

$output180 .= '
	<title>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments181 = array();
$arguments181['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'title', $renderingContext);
$arguments181['keepQuotes'] = false;
$arguments181['encoding'] = 'UTF-8';
$arguments181['doubleEncode'] = true;
$renderChildrenClosure182 = function() use ($renderingContext, $self) {
return NULL;
};
$value183 = ($arguments181['value'] !== NULL ? $arguments181['value'] : $renderChildrenClosure182());

$output180 .= !is_string($value183) && !(is_object($value183) && method_exists($value183, '__toString')) ? $value183 : htmlspecialchars($value183, ($arguments181['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments181['encoding'], $arguments181['doubleEncode']);

$output180 .= '</title>

	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments184 = array();
// Rendering Boolean node
$arguments184['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleConfiguration.additionalResources.styleSheets', $renderingContext));
$arguments184['then'] = NULL;
$arguments184['else'] = NULL;
$renderChildrenClosure185 = function() use ($renderingContext, $self) {
$output186 = '';

$output186 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments187 = array();
$arguments187['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleConfiguration.additionalResources.styleSheets', $renderingContext);
$arguments187['as'] = 'additionalResource';
$arguments187['key'] = '';
$arguments187['reverse'] = false;
$arguments187['iteration'] = NULL;
$renderChildrenClosure188 = function() use ($renderingContext, $self) {
$output189 = '';

$output189 .= '
			<link rel="stylesheet" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments190 = array();
$arguments190['path'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'additionalResource', $renderingContext);
$arguments190['package'] = NULL;
$arguments190['resource'] = NULL;
$arguments190['localize'] = true;
$renderChildrenClosure191 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper192 = $self->getViewHelper('$viewHelper192', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper192->setArguments($arguments190);
$viewHelper192->setRenderingContext($renderingContext);
$viewHelper192->setRenderChildrenClosure($renderChildrenClosure191);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output189 .= $viewHelper192->initializeArgumentsAndRender();

$output189 .= '" />
		';
return $output189;
};

$output186 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments187, $renderChildrenClosure188, $renderingContext);

$output186 .= '
	';
return $output186;
};
$viewHelper193 = $self->getViewHelper('$viewHelper193', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper193->setArguments($arguments184);
$viewHelper193->setRenderingContext($renderingContext);
$viewHelper193->setRenderChildrenClosure($renderChildrenClosure185);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output180 .= $viewHelper193->initializeArgumentsAndRender();

$output180 .= '

	<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments194 = array();
$arguments194['path'] = 'Library/jquery/jquery-2.0.3.js';
$arguments194['package'] = NULL;
$arguments194['resource'] = NULL;
$arguments194['localize'] = true;
$renderChildrenClosure195 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper196 = $self->getViewHelper('$viewHelper196', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper196->setArguments($arguments194);
$viewHelper196->setRenderingContext($renderingContext);
$viewHelper196->setRenderChildrenClosure($renderChildrenClosure195);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output180 .= $viewHelper196->initializeArgumentsAndRender();

$output180 .= '"></script>
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments197 = array();
// Rendering Boolean node
$arguments197['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleConfiguration.additionalResources.javaScripts', $renderingContext));
$arguments197['then'] = NULL;
$arguments197['else'] = NULL;
$renderChildrenClosure198 = function() use ($renderingContext, $self) {
$output199 = '';

$output199 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments200 = array();
$arguments200['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleConfiguration.additionalResources.javaScripts', $renderingContext);
$arguments200['as'] = 'additionalResource';
$arguments200['key'] = '';
$arguments200['reverse'] = false;
$arguments200['iteration'] = NULL;
$renderChildrenClosure201 = function() use ($renderingContext, $self) {
$output202 = '';

$output202 .= '
			<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments203 = array();
$arguments203['path'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'additionalResource', $renderingContext);
$arguments203['package'] = NULL;
$arguments203['resource'] = NULL;
$arguments203['localize'] = true;
$renderChildrenClosure204 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper205 = $self->getViewHelper('$viewHelper205', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper205->setArguments($arguments203);
$viewHelper205->setRenderingContext($renderingContext);
$viewHelper205->setRenderChildrenClosure($renderChildrenClosure204);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output202 .= $viewHelper205->initializeArgumentsAndRender();

$output202 .= '"></script>
		';
return $output202;
};

$output199 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments200, $renderChildrenClosure201, $renderingContext);

$output199 .= '
	';
return $output199;
};
$viewHelper206 = $self->getViewHelper('$viewHelper206', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper206->setArguments($arguments197);
$viewHelper206->setRenderingContext($renderingContext);
$viewHelper206->setRenderChildrenClosure($renderChildrenClosure198);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output180 .= $viewHelper206->initializeArgumentsAndRender();

$output180 .= '

	<script type="text/javascript">
		// TODO: Get rid of those global variables
		';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\JavascriptConfigurationViewHelper
$arguments207 = array();
$renderChildrenClosure208 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper209 = $self->getViewHelper('$viewHelper209', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\JavascriptConfigurationViewHelper');
$viewHelper209->setArguments($arguments207);
$viewHelper209->setRenderingContext($renderingContext);
$viewHelper209->setRenderChildrenClosure($renderChildrenClosure208);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\JavascriptConfigurationViewHelper

$output180 .= $viewHelper209->initializeArgumentsAndRender();

$output180 .= '
	</script>

	<link rel="neos-menudata" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments210 = array();
$arguments210['action'] = 'index';
$arguments210['controller'] = 'Backend\\Menu';
$arguments210['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments210['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array211 = array();
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$arguments212 = array();
$renderChildrenClosure213 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper214 = $self->getViewHelper('$viewHelper214', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper');
$viewHelper214->setArguments($arguments212);
$viewHelper214->setRenderingContext($renderingContext);
$viewHelper214->setRenderChildrenClosure($renderChildrenClosure213);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$array211['version'] = $viewHelper214->initializeArgumentsAndRender();
$arguments210['arguments'] = $array211;
$arguments210['subpackage'] = NULL;
$arguments210['section'] = '';
$arguments210['format'] = '';
$arguments210['additionalParams'] = array (
);
$arguments210['addQueryString'] = false;
$arguments210['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments210['useParentRequest'] = false;
$renderChildrenClosure215 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper216 = $self->getViewHelper('$viewHelper216', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper216->setArguments($arguments210);
$viewHelper216->setRenderingContext($renderingContext);
$viewHelper216->setRenderChildrenClosure($renderChildrenClosure215);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output180 .= $viewHelper216->initializeArgumentsAndRender();

$output180 .= '" />
	<!-- TODO: Make sure the schema information and edit / preview panel data isn\'t necessary in backend modules -->
	<link rel="neos-vieschema" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments217 = array();
$arguments217['action'] = 'vieSchema';
$arguments217['controller'] = 'Backend\\Schema';
$arguments217['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments217['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array218 = array();
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$arguments219 = array();
$renderChildrenClosure220 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper221 = $self->getViewHelper('$viewHelper221', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper');
$viewHelper221->setArguments($arguments219);
$viewHelper221->setRenderingContext($renderingContext);
$viewHelper221->setRenderChildrenClosure($renderChildrenClosure220);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$array218['version'] = $viewHelper221->initializeArgumentsAndRender();
$arguments217['arguments'] = $array218;
$arguments217['subpackage'] = NULL;
$arguments217['section'] = '';
$arguments217['format'] = '';
$arguments217['additionalParams'] = array (
);
$arguments217['addQueryString'] = false;
$arguments217['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments217['useParentRequest'] = false;
$renderChildrenClosure222 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper223 = $self->getViewHelper('$viewHelper223', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper223->setArguments($arguments217);
$viewHelper223->setRenderingContext($renderingContext);
$viewHelper223->setRenderChildrenClosure($renderChildrenClosure222);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output180 .= $viewHelper223->initializeArgumentsAndRender();

$output180 .= '" />
	<link rel="neos-nodetypeschema" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments224 = array();
$arguments224['action'] = 'nodeTypeSchema';
$arguments224['controller'] = 'Backend\\Schema';
$arguments224['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments224['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array225 = array();
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$arguments226 = array();
$renderChildrenClosure227 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper228 = $self->getViewHelper('$viewHelper228', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper');
$viewHelper228->setArguments($arguments226);
$viewHelper228->setRenderingContext($renderingContext);
$viewHelper228->setRenderChildrenClosure($renderChildrenClosure227);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$array225['version'] = $viewHelper228->initializeArgumentsAndRender();
$arguments224['arguments'] = $array225;
$arguments224['subpackage'] = NULL;
$arguments224['section'] = '';
$arguments224['format'] = '';
$arguments224['additionalParams'] = array (
);
$arguments224['addQueryString'] = false;
$arguments224['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments224['useParentRequest'] = false;
$renderChildrenClosure229 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper230 = $self->getViewHelper('$viewHelper230', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper230->setArguments($arguments224);
$viewHelper230->setRenderingContext($renderingContext);
$viewHelper230->setRenderChildrenClosure($renderChildrenClosure229);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output180 .= $viewHelper230->initializeArgumentsAndRender();

$output180 .= '" />
	<link rel="neos-editpreviewdata" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments231 = array();
$arguments231['action'] = 'editPreview';
$arguments231['controller'] = 'Backend\\Settings';
$arguments231['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments231['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array232 = array();
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$arguments233 = array();
$renderChildrenClosure234 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper235 = $self->getViewHelper('$viewHelper235', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper');
$viewHelper235->setArguments($arguments233);
$viewHelper235->setRenderingContext($renderingContext);
$viewHelper235->setRenderChildrenClosure($renderChildrenClosure234);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$array232['version'] = $viewHelper235->initializeArgumentsAndRender();
$arguments231['arguments'] = $array232;
$arguments231['subpackage'] = NULL;
$arguments231['section'] = '';
$arguments231['format'] = '';
$arguments231['additionalParams'] = array (
);
$arguments231['addQueryString'] = false;
$arguments231['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments231['useParentRequest'] = false;
$renderChildrenClosure236 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper237 = $self->getViewHelper('$viewHelper237', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper237->setArguments($arguments231);
$viewHelper237->setRenderingContext($renderingContext);
$viewHelper237->setRenderChildrenClosure($renderChildrenClosure236);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output180 .= $viewHelper237->initializeArgumentsAndRender();

$output180 .= '" />

	<link rel="stylesheet" type="text/css" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments238 = array();
$output239 = '';

$output239 .= 'Styles/Includes';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments240 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper
$arguments241 = array();
$renderChildrenClosure242 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper243 = $self->getViewHelper('$viewHelper243', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper');
$viewHelper243->setArguments($arguments241);
$viewHelper243->setRenderingContext($renderingContext);
$viewHelper243->setRenderChildrenClosure($renderChildrenClosure242);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper
$arguments240['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean($viewHelper243->initializeArgumentsAndRender());
$arguments240['then'] = '-built';
$arguments240['else'] = NULL;
$renderChildrenClosure244 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper245 = $self->getViewHelper('$viewHelper245', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper245->setArguments($arguments240);
$viewHelper245->setRenderingContext($renderingContext);
$viewHelper245->setRenderChildrenClosure($renderChildrenClosure244);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output239 .= $viewHelper245->initializeArgumentsAndRender();

$output239 .= '.css';
$arguments238['path'] = $output239;
$arguments238['package'] = 'TYPO3.Neos';
$arguments238['resource'] = NULL;
$arguments238['localize'] = true;
$renderChildrenClosure246 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper247 = $self->getViewHelper('$viewHelper247', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper247->setArguments($arguments238);
$viewHelper247->setRenderingContext($renderingContext);
$viewHelper247->setRenderChildrenClosure($renderChildrenClosure246);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output180 .= $viewHelper247->initializeArgumentsAndRender();

$output180 .= '" />
';
return $output180;
};

$output174 .= '';

$output174 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments248 = array();
$arguments248['name'] = 'body';
$renderChildrenClosure249 = function() use ($renderingContext, $self) {
$output250 = '';

$output250 .= '
	<body class="neos neos-module neos-controls neos-module-';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments251 = array();
$arguments251['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleClass', $renderingContext);
$arguments251['keepQuotes'] = false;
$arguments251['encoding'] = 'UTF-8';
$arguments251['doubleEncode'] = true;
$renderChildrenClosure252 = function() use ($renderingContext, $self) {
return NULL;
};
$value253 = ($arguments251['value'] !== NULL ? $arguments251['value'] : $renderChildrenClosure252());

$output250 .= !is_string($value253) && !(is_object($value253) && method_exists($value253, '__toString')) ? $value253 : htmlspecialchars($value253, ($arguments251['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments251['encoding'], $arguments251['doubleEncode']);

$output250 .= '">
		<div class="neos-module-wrap">
			<ul class="neos-breadcrumb">
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments254 = array();
$arguments254['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleBreadcrumb', $renderingContext);
$arguments254['key'] = 'path';
$arguments254['as'] = 'configuration';
$arguments254['iteration'] = 'iterator';
$arguments254['reverse'] = false;
$renderChildrenClosure255 = function() use ($renderingContext, $self) {
$output256 = '';

$output256 .= '
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments257 = array();
// Rendering Boolean node
$arguments257['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configuration.hideInMenu', $renderingContext));
$arguments257['then'] = NULL;
$arguments257['else'] = NULL;
$renderChildrenClosure258 = function() use ($renderingContext, $self) {
$output259 = '';

$output259 .= '
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments260 = array();
$renderChildrenClosure261 = function() use ($renderingContext, $self) {
$output262 = '';

$output262 .= '
							<li>
								';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper
$arguments263 = array();
$arguments263['path'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'path', $renderingContext);
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments264 = array();
// Rendering Boolean node
$arguments264['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'iterator.isLast', $renderingContext));
$arguments264['then'] = 'active';
$arguments264['else'] = NULL;
$renderChildrenClosure265 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper266 = $self->getViewHelper('$viewHelper266', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper266->setArguments($arguments264);
$viewHelper266->setRenderingContext($renderingContext);
$viewHelper266->setRenderChildrenClosure($renderChildrenClosure265);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments263['class'] = $viewHelper266->initializeArgumentsAndRender();
$arguments263['additionalAttributes'] = NULL;
$arguments263['data'] = NULL;
$arguments263['action'] = NULL;
$arguments263['arguments'] = array (
);
$arguments263['section'] = '';
$arguments263['format'] = '';
$arguments263['additionalParams'] = array (
);
$arguments263['addQueryString'] = false;
$arguments263['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments263['dir'] = NULL;
$arguments263['id'] = NULL;
$arguments263['lang'] = NULL;
$arguments263['style'] = NULL;
$arguments263['title'] = NULL;
$arguments263['accesskey'] = NULL;
$arguments263['tabindex'] = NULL;
$arguments263['onclick'] = NULL;
$arguments263['name'] = NULL;
$arguments263['rel'] = NULL;
$arguments263['rev'] = NULL;
$arguments263['target'] = NULL;
$renderChildrenClosure267 = function() use ($renderingContext, $self) {
$output268 = '';

$output268 .= '<i class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments269 = array();
$arguments269['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configuration.icon', $renderingContext);
$arguments269['keepQuotes'] = false;
$arguments269['encoding'] = 'UTF-8';
$arguments269['doubleEncode'] = true;
$renderChildrenClosure270 = function() use ($renderingContext, $self) {
return NULL;
};
$value271 = ($arguments269['value'] !== NULL ? $arguments269['value'] : $renderChildrenClosure270());

$output268 .= !is_string($value271) && !(is_object($value271) && method_exists($value271, '__toString')) ? $value271 : htmlspecialchars($value271, ($arguments269['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments269['encoding'], $arguments269['doubleEncode']);

$output268 .= '"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments272 = array();
$arguments272['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configuration.label', $renderingContext);
$arguments272['keepQuotes'] = false;
$arguments272['encoding'] = 'UTF-8';
$arguments272['doubleEncode'] = true;
$renderChildrenClosure273 = function() use ($renderingContext, $self) {
return NULL;
};
$value274 = ($arguments272['value'] !== NULL ? $arguments272['value'] : $renderChildrenClosure273());

$output268 .= !is_string($value274) && !(is_object($value274) && method_exists($value274, '__toString')) ? $value274 : htmlspecialchars($value274, ($arguments272['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments272['encoding'], $arguments272['doubleEncode']);
return $output268;
};
$viewHelper275 = $self->getViewHelper('$viewHelper275', $renderingContext, 'TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper');
$viewHelper275->setArguments($arguments263);
$viewHelper275->setRenderingContext($renderingContext);
$viewHelper275->setRenderChildrenClosure($renderChildrenClosure267);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper

$output262 .= $viewHelper275->initializeArgumentsAndRender();

$output262 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments276 = array();
// Rendering Boolean node
$arguments276['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'iterator.isLast', $renderingContext));
$arguments276['then'] = NULL;
$arguments276['else'] = NULL;
$renderChildrenClosure277 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments278 = array();
$renderChildrenClosure279 = function() use ($renderingContext, $self) {
return '<span class="neos-divider">/</span>';
};
$viewHelper280 = $self->getViewHelper('$viewHelper280', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper280->setArguments($arguments278);
$viewHelper280->setRenderingContext($renderingContext);
$viewHelper280->setRenderChildrenClosure($renderChildrenClosure279);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
return $viewHelper280->initializeArgumentsAndRender();
};
$arguments276['__elseClosure'] = function() use ($renderingContext, $self) {
return '<span class="neos-divider">/</span>';
};
$viewHelper281 = $self->getViewHelper('$viewHelper281', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper281->setArguments($arguments276);
$viewHelper281->setRenderingContext($renderingContext);
$viewHelper281->setRenderChildrenClosure($renderChildrenClosure277);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output262 .= $viewHelper281->initializeArgumentsAndRender();

$output262 .= '
							</li>
						';
return $output262;
};
$viewHelper282 = $self->getViewHelper('$viewHelper282', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper282->setArguments($arguments260);
$viewHelper282->setRenderingContext($renderingContext);
$viewHelper282->setRenderChildrenClosure($renderChildrenClosure261);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output259 .= $viewHelper282->initializeArgumentsAndRender();

$output259 .= '
					';
return $output259;
};
$arguments257['__elseClosure'] = function() use ($renderingContext, $self) {
$output283 = '';

$output283 .= '
							<li>
								';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper
$arguments284 = array();
$arguments284['path'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'path', $renderingContext);
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments285 = array();
// Rendering Boolean node
$arguments285['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'iterator.isLast', $renderingContext));
$arguments285['then'] = 'active';
$arguments285['else'] = NULL;
$renderChildrenClosure286 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper287 = $self->getViewHelper('$viewHelper287', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper287->setArguments($arguments285);
$viewHelper287->setRenderingContext($renderingContext);
$viewHelper287->setRenderChildrenClosure($renderChildrenClosure286);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments284['class'] = $viewHelper287->initializeArgumentsAndRender();
$arguments284['additionalAttributes'] = NULL;
$arguments284['data'] = NULL;
$arguments284['action'] = NULL;
$arguments284['arguments'] = array (
);
$arguments284['section'] = '';
$arguments284['format'] = '';
$arguments284['additionalParams'] = array (
);
$arguments284['addQueryString'] = false;
$arguments284['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments284['dir'] = NULL;
$arguments284['id'] = NULL;
$arguments284['lang'] = NULL;
$arguments284['style'] = NULL;
$arguments284['title'] = NULL;
$arguments284['accesskey'] = NULL;
$arguments284['tabindex'] = NULL;
$arguments284['onclick'] = NULL;
$arguments284['name'] = NULL;
$arguments284['rel'] = NULL;
$arguments284['rev'] = NULL;
$arguments284['target'] = NULL;
$renderChildrenClosure288 = function() use ($renderingContext, $self) {
$output289 = '';

$output289 .= '<i class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments290 = array();
$arguments290['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configuration.icon', $renderingContext);
$arguments290['keepQuotes'] = false;
$arguments290['encoding'] = 'UTF-8';
$arguments290['doubleEncode'] = true;
$renderChildrenClosure291 = function() use ($renderingContext, $self) {
return NULL;
};
$value292 = ($arguments290['value'] !== NULL ? $arguments290['value'] : $renderChildrenClosure291());

$output289 .= !is_string($value292) && !(is_object($value292) && method_exists($value292, '__toString')) ? $value292 : htmlspecialchars($value292, ($arguments290['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments290['encoding'], $arguments290['doubleEncode']);

$output289 .= '"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments293 = array();
$arguments293['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configuration.label', $renderingContext);
$arguments293['keepQuotes'] = false;
$arguments293['encoding'] = 'UTF-8';
$arguments293['doubleEncode'] = true;
$renderChildrenClosure294 = function() use ($renderingContext, $self) {
return NULL;
};
$value295 = ($arguments293['value'] !== NULL ? $arguments293['value'] : $renderChildrenClosure294());

$output289 .= !is_string($value295) && !(is_object($value295) && method_exists($value295, '__toString')) ? $value295 : htmlspecialchars($value295, ($arguments293['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments293['encoding'], $arguments293['doubleEncode']);
return $output289;
};
$viewHelper296 = $self->getViewHelper('$viewHelper296', $renderingContext, 'TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper');
$viewHelper296->setArguments($arguments284);
$viewHelper296->setRenderingContext($renderingContext);
$viewHelper296->setRenderChildrenClosure($renderChildrenClosure288);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper

$output283 .= $viewHelper296->initializeArgumentsAndRender();

$output283 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments297 = array();
// Rendering Boolean node
$arguments297['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'iterator.isLast', $renderingContext));
$arguments297['then'] = NULL;
$arguments297['else'] = NULL;
$renderChildrenClosure298 = function() use ($renderingContext, $self) {
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments299 = array();
$renderChildrenClosure300 = function() use ($renderingContext, $self) {
return '<span class="neos-divider">/</span>';
};
$viewHelper301 = $self->getViewHelper('$viewHelper301', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper301->setArguments($arguments299);
$viewHelper301->setRenderingContext($renderingContext);
$viewHelper301->setRenderChildrenClosure($renderChildrenClosure300);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
return $viewHelper301->initializeArgumentsAndRender();
};
$arguments297['__elseClosure'] = function() use ($renderingContext, $self) {
return '<span class="neos-divider">/</span>';
};
$viewHelper302 = $self->getViewHelper('$viewHelper302', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper302->setArguments($arguments297);
$viewHelper302->setRenderingContext($renderingContext);
$viewHelper302->setRenderChildrenClosure($renderChildrenClosure298);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output283 .= $viewHelper302->initializeArgumentsAndRender();

$output283 .= '
							</li>
						';
return $output283;
};
$viewHelper303 = $self->getViewHelper('$viewHelper303', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper303->setArguments($arguments257);
$viewHelper303->setRenderingContext($renderingContext);
$viewHelper303->setRenderChildrenClosure($renderChildrenClosure258);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output256 .= $viewHelper303->initializeArgumentsAndRender();

$output256 .= '
				';
return $output256;
};

$output250 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments254, $renderChildrenClosure255, $renderingContext);

$output250 .= '
			</ul>
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments304 = array();
// Rendering Boolean node
$arguments304['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleContents', $renderingContext));
$arguments304['then'] = NULL;
$arguments304['else'] = NULL;
$renderChildrenClosure305 = function() use ($renderingContext, $self) {
$output306 = '';

$output306 .= '
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper
$arguments307 = array();
$arguments307['value'] = NULL;
$renderChildrenClosure308 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleContents', $renderingContext);
};
$viewHelper309 = $self->getViewHelper('$viewHelper309', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Format\RawViewHelper');
$viewHelper309->setArguments($arguments307);
$viewHelper309->setRenderingContext($renderingContext);
$viewHelper309->setRenderChildrenClosure($renderChildrenClosure308);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Format\RawViewHelper

$output306 .= $viewHelper309->initializeArgumentsAndRender();

$output306 .= '
			';
return $output306;
};
$viewHelper310 = $self->getViewHelper('$viewHelper310', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper310->setArguments($arguments304);
$viewHelper310->setRenderingContext($renderingContext);
$viewHelper310->setRenderChildrenClosure($renderChildrenClosure305);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output250 .= $viewHelper310->initializeArgumentsAndRender();

$output250 .= '
			<div id="neos-application" class="neos">
				<div id="neos-top-bar">
					<div id="neos-top-bar-right">
						<div id="neos-user-actions">
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments311 = array();
$arguments311['partial'] = 'Backend/UserMenu';
$arguments311['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments311['section'] = NULL;
$arguments311['optional'] = false;
$renderChildrenClosure312 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper313 = $self->getViewHelper('$viewHelper313', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper313->setArguments($arguments311);
$viewHelper313->setRenderingContext($renderingContext);
$viewHelper313->setRenderChildrenClosure($renderChildrenClosure312);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output250 .= $viewHelper313->initializeArgumentsAndRender();

$output250 .= '
						</div>
					</div>
				</div>
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments314 = array();
$arguments314['partial'] = 'Backend/Menu';
// Rendering Array
$array315 = array();
$array315['sites'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'sites', $renderingContext);
$array315['modules'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'modules', $renderingContext);
$array315['modulePath'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'moduleConfiguration.path', $renderingContext);
$arguments314['arguments'] = $array315;
$arguments314['section'] = NULL;
$arguments314['optional'] = false;
$renderChildrenClosure316 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper317 = $self->getViewHelper('$viewHelper317', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper317->setArguments($arguments314);
$viewHelper317->setRenderingContext($renderingContext);
$viewHelper317->setRenderChildrenClosure($renderChildrenClosure316);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output250 .= $viewHelper317->initializeArgumentsAndRender();

$output250 .= '
			</div>
		</div>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments318 = array();
$arguments318['path'] = '2/js/bootstrap.min.js';
$arguments318['package'] = 'TYPO3.Twitter.Bootstrap';
$arguments318['resource'] = NULL;
$arguments318['localize'] = true;
$renderChildrenClosure319 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper320 = $self->getViewHelper('$viewHelper320', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper320->setArguments($arguments318);
$viewHelper320->setRenderingContext($renderingContext);
$viewHelper320->setRenderChildrenClosure($renderChildrenClosure319);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output250 .= $viewHelper320->initializeArgumentsAndRender();

$output250 .= '"></script>
		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments321 = array();
$arguments321['path'] = 'Library/fixedsticky/fixedsticky.js';
$arguments321['package'] = 'TYPO3.Neos';
$arguments321['resource'] = NULL;
$arguments321['localize'] = true;
$renderChildrenClosure322 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper323 = $self->getViewHelper('$viewHelper323', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper323->setArguments($arguments321);
$viewHelper323->setRenderingContext($renderingContext);
$viewHelper323->setRenderChildrenClosure($renderChildrenClosure322);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output250 .= $viewHelper323->initializeArgumentsAndRender();

$output250 .= '"></script>

		<script type="text/javascript">
			(function($) {
				$(function() {
					$(\'.neos-footer\').fixedsticky();
				});
			})(jQuery);
		</script>

		<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments324 = array();
$arguments324['path'] = 'Library/requirejs/require.js';
$arguments324['package'] = 'TYPO3.Neos';
$arguments324['resource'] = NULL;
$arguments324['localize'] = true;
$renderChildrenClosure325 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper326 = $self->getViewHelper('$viewHelper326', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper326->setArguments($arguments324);
$viewHelper326->setRenderingContext($renderingContext);
$viewHelper326->setRenderChildrenClosure($renderChildrenClosure325);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output250 .= $viewHelper326->initializeArgumentsAndRender();

$output250 .= '"></script>
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments327 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper
$arguments328 = array();
$renderChildrenClosure329 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper330 = $self->getViewHelper('$viewHelper330', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper');
$viewHelper330->setArguments($arguments328);
$viewHelper330->setRenderingContext($renderingContext);
$viewHelper330->setRenderChildrenClosure($renderChildrenClosure329);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ShouldLoadMinifiedJavascriptViewHelper
$arguments327['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean($viewHelper330->initializeArgumentsAndRender());
$arguments327['then'] = NULL;
$arguments327['else'] = NULL;
$renderChildrenClosure331 = function() use ($renderingContext, $self) {
$output332 = '';

$output332 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments333 = array();
$renderChildrenClosure334 = function() use ($renderingContext, $self) {
$output335 = '';

$output335 .= '
				<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments336 = array();
$arguments336['path'] = 'JavaScript/ContentModule-built.js';
$arguments336['package'] = 'TYPO3.Neos';
$arguments336['resource'] = NULL;
$arguments336['localize'] = true;
$renderChildrenClosure337 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper338 = $self->getViewHelper('$viewHelper338', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper338->setArguments($arguments336);
$viewHelper338->setRenderingContext($renderingContext);
$viewHelper338->setRenderChildrenClosure($renderChildrenClosure337);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output335 .= $viewHelper338->initializeArgumentsAndRender();

$output335 .= '"></script>
			';
return $output335;
};
$viewHelper339 = $self->getViewHelper('$viewHelper339', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper339->setArguments($arguments333);
$viewHelper339->setRenderingContext($renderingContext);
$viewHelper339->setRenderChildrenClosure($renderChildrenClosure334);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output332 .= $viewHelper339->initializeArgumentsAndRender();

$output332 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments340 = array();
$renderChildrenClosure341 = function() use ($renderingContext, $self) {
$output342 = '';

$output342 .= '
				<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments343 = array();
$arguments343['path'] = 'JavaScript/ContentModuleBootstrap.js';
$arguments343['package'] = 'TYPO3.Neos';
$arguments343['resource'] = NULL;
$arguments343['localize'] = true;
$renderChildrenClosure344 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper345 = $self->getViewHelper('$viewHelper345', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper345->setArguments($arguments343);
$viewHelper345->setRenderingContext($renderingContext);
$viewHelper345->setRenderChildrenClosure($renderChildrenClosure344);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output342 .= $viewHelper345->initializeArgumentsAndRender();

$output342 .= '"></script>
			';
return $output342;
};
$viewHelper346 = $self->getViewHelper('$viewHelper346', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper346->setArguments($arguments340);
$viewHelper346->setRenderingContext($renderingContext);
$viewHelper346->setRenderChildrenClosure($renderChildrenClosure341);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output332 .= $viewHelper346->initializeArgumentsAndRender();

$output332 .= '
		';
return $output332;
};
$arguments327['__thenClosure'] = function() use ($renderingContext, $self) {
$output347 = '';

$output347 .= '
				<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments348 = array();
$arguments348['path'] = 'JavaScript/ContentModule-built.js';
$arguments348['package'] = 'TYPO3.Neos';
$arguments348['resource'] = NULL;
$arguments348['localize'] = true;
$renderChildrenClosure349 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper350 = $self->getViewHelper('$viewHelper350', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper350->setArguments($arguments348);
$viewHelper350->setRenderingContext($renderingContext);
$viewHelper350->setRenderChildrenClosure($renderChildrenClosure349);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output347 .= $viewHelper350->initializeArgumentsAndRender();

$output347 .= '"></script>
			';
return $output347;
};
$arguments327['__elseClosure'] = function() use ($renderingContext, $self) {
$output351 = '';

$output351 .= '
				<script src="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments352 = array();
$arguments352['path'] = 'JavaScript/ContentModuleBootstrap.js';
$arguments352['package'] = 'TYPO3.Neos';
$arguments352['resource'] = NULL;
$arguments352['localize'] = true;
$renderChildrenClosure353 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper354 = $self->getViewHelper('$viewHelper354', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper354->setArguments($arguments352);
$viewHelper354->setRenderingContext($renderingContext);
$viewHelper354->setRenderChildrenClosure($renderChildrenClosure353);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output351 .= $viewHelper354->initializeArgumentsAndRender();

$output351 .= '"></script>
			';
return $output351;
};
$viewHelper355 = $self->getViewHelper('$viewHelper355', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper355->setArguments($arguments327);
$viewHelper355->setRenderingContext($renderingContext);
$viewHelper355->setRenderChildrenClosure($renderChildrenClosure331);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output250 .= $viewHelper355->initializeArgumentsAndRender();

$output250 .= '
	</body>
';
return $output250;
};

$output174 .= '';

$output174 .= '
';

return $output174;
}


}
#0             97368     