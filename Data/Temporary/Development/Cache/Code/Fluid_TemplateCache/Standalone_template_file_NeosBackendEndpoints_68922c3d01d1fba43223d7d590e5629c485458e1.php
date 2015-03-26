<?php class FluidCache_Standalone_template_file_NeosBackendEndpoints_68922c3d01d1fba43223d7d590e5629c485458e1 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Security\IfAccessViewHelper
$arguments1 = array();
$arguments1['resource'] = 'TYPO3_Neos_Backend_GeneralAccess';
$arguments1['then'] = NULL;
$arguments1['else'] = NULL;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
$output3 = '';

$output3 .= '
	<meta name="neos-username" content="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments4 = array();
$arguments4['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'account.accountIdentifier', $renderingContext);
$arguments4['keepQuotes'] = false;
$arguments4['encoding'] = 'UTF-8';
$arguments4['doubleEncode'] = true;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
return NULL;
};
$value6 = ($arguments4['value'] !== NULL ? $arguments4['value'] : $renderChildrenClosure5());

$output3 .= !is_string($value6) && !(is_object($value6) && method_exists($value6, '__toString')) ? $value6 : htmlspecialchars($value6, ($arguments4['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments4['encoding'], $arguments4['doubleEncode']);

$output3 .= '" />
	<meta name="neos-workspace" content="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments7 = array();
$arguments7['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'node.context.workspace.name', $renderingContext);
$arguments7['keepQuotes'] = false;
$arguments7['encoding'] = 'UTF-8';
$arguments7['doubleEncode'] = true;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
return NULL;
};
$value9 = ($arguments7['value'] !== NULL ? $arguments7['value'] : $renderChildrenClosure8());

$output3 .= !is_string($value9) && !(is_object($value9) && method_exists($value9, '__toString')) ? $value9 : htmlspecialchars($value9, ($arguments7['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments7['encoding'], $arguments7['doubleEncode']);

$output3 .= '" />
	<meta name="neos-csrf-token" content="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Security\CsrfTokenViewHelper
$arguments10 = array();
$renderChildrenClosure11 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper12 = $self->getViewHelper('$viewHelper12', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Security\CsrfTokenViewHelper');
$viewHelper12->setArguments($arguments10);
$viewHelper12->setRenderingContext($renderingContext);
$viewHelper12->setRenderChildrenClosure($renderChildrenClosure11);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Security\CsrfTokenViewHelper

$output3 .= $viewHelper12->initializeArgumentsAndRender();

$output3 .= '" />

	<link rel="neos-site" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments13 = array();
$arguments13['action'] = 'show';
$arguments13['controller'] = 'Frontend\\Node';
$arguments13['package'] = 'TYPO3.Neos';
$arguments13['format'] = 'html';
// Rendering Array
$array14 = array();
$array14['node'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'node.context.currentSiteNode', $renderingContext);
$arguments13['arguments'] = $array14;
// Rendering Boolean node
$arguments13['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments13['subpackage'] = NULL;
$arguments13['section'] = '';
$arguments13['additionalParams'] = array (
);
$arguments13['addQueryString'] = false;
$arguments13['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments13['useParentRequest'] = false;
$renderChildrenClosure15 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper16 = $self->getViewHelper('$viewHelper16', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper16->setArguments($arguments13);
$viewHelper16->setRenderingContext($renderingContext);
$viewHelper16->setRenderChildrenClosure($renderChildrenClosure15);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper16->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-nodes" type="application/vnd.typo3.neos.nodes" data-current-workspace="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments17 = array();
$arguments17['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'node.context.workspace.name', $renderingContext);
$arguments17['keepQuotes'] = false;
$arguments17['encoding'] = 'UTF-8';
$arguments17['doubleEncode'] = true;
$renderChildrenClosure18 = function() use ($renderingContext, $self) {
return NULL;
};
$value19 = ($arguments17['value'] !== NULL ? $arguments17['value'] : $renderChildrenClosure18());

$output3 .= !is_string($value19) && !(is_object($value19) && method_exists($value19, '__toString')) ? $value19 : htmlspecialchars($value19, ($arguments17['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments17['encoding'], $arguments17['doubleEncode']);

$output3 .= '" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments20 = array();
$arguments20['action'] = 'index';
$arguments20['controller'] = 'Service\\Nodes';
$arguments20['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments20['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments20['arguments'] = array (
);
$arguments20['subpackage'] = NULL;
$arguments20['section'] = '';
$arguments20['format'] = '';
$arguments20['additionalParams'] = array (
);
$arguments20['addQueryString'] = false;
$arguments20['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments20['useParentRequest'] = false;
$renderChildrenClosure21 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper22 = $self->getViewHelper('$viewHelper22', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper22->setArguments($arguments20);
$viewHelper22->setRenderingContext($renderingContext);
$viewHelper22->setRenderChildrenClosure($renderChildrenClosure21);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper22->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-assets" type="application/vnd.typo3.neos.assets" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments23 = array();
$arguments23['action'] = 'index';
$arguments23['controller'] = 'Service\\Assets';
$arguments23['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments23['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments23['arguments'] = array (
);
$arguments23['subpackage'] = NULL;
$arguments23['section'] = '';
$arguments23['format'] = '';
$arguments23['additionalParams'] = array (
);
$arguments23['addQueryString'] = false;
$arguments23['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments23['useParentRequest'] = false;
$renderChildrenClosure24 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper25 = $self->getViewHelper('$viewHelper25', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper25->setArguments($arguments23);
$viewHelper25->setRenderingContext($renderingContext);
$viewHelper25->setRenderChildrenClosure($renderChildrenClosure24);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper25->initializeArgumentsAndRender();

$output3 .= '" />

	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\AliasViewHelper
$arguments26 = array();
// Rendering Array
$array27 = array();
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$arguments28 = array();
$renderChildrenClosure29 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper30 = $self->getViewHelper('$viewHelper30', $renderingContext, 'TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper');
$viewHelper30->setArguments($arguments28);
$viewHelper30->setRenderingContext($renderingContext);
$viewHelper30->setRenderChildrenClosure($renderChildrenClosure29);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Backend\ConfigurationCacheVersionViewHelper
$array27['configurationCacheIdentifier'] = $viewHelper30->initializeArgumentsAndRender();
$arguments26['map'] = $array27;
$renderChildrenClosure31 = function() use ($renderingContext, $self) {
$output32 = '';

$output32 .= '
		<link rel="neos-vieschema" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments33 = array();
$arguments33['action'] = 'vieSchema';
$arguments33['controller'] = 'Backend\\Schema';
$arguments33['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments33['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array34 = array();
$array34['version'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configurationCacheIdentifier', $renderingContext);
$arguments33['arguments'] = $array34;
$arguments33['subpackage'] = NULL;
$arguments33['section'] = '';
$arguments33['format'] = '';
$arguments33['additionalParams'] = array (
);
$arguments33['addQueryString'] = false;
$arguments33['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments33['useParentRequest'] = false;
$renderChildrenClosure35 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper36 = $self->getViewHelper('$viewHelper36', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper36->setArguments($arguments33);
$viewHelper36->setRenderingContext($renderingContext);
$viewHelper36->setRenderChildrenClosure($renderChildrenClosure35);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output32 .= $viewHelper36->initializeArgumentsAndRender();

$output32 .= '" />
		<link rel="neos-nodetypeschema" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments37 = array();
$arguments37['action'] = 'nodeTypeSchema';
$arguments37['controller'] = 'Backend\\Schema';
$arguments37['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments37['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array38 = array();
$array38['version'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configurationCacheIdentifier', $renderingContext);
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
$renderChildrenClosure39 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper40 = $self->getViewHelper('$viewHelper40', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper40->setArguments($arguments37);
$viewHelper40->setRenderingContext($renderingContext);
$viewHelper40->setRenderChildrenClosure($renderChildrenClosure39);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output32 .= $viewHelper40->initializeArgumentsAndRender();

$output32 .= '" />
		<link rel="neos-menudata" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments41 = array();
$arguments41['action'] = 'index';
$arguments41['controller'] = 'Backend\\Menu';
$arguments41['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments41['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array42 = array();
$array42['version'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configurationCacheIdentifier', $renderingContext);
$arguments41['arguments'] = $array42;
$arguments41['subpackage'] = NULL;
$arguments41['section'] = '';
$arguments41['format'] = '';
$arguments41['additionalParams'] = array (
);
$arguments41['addQueryString'] = false;
$arguments41['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments41['useParentRequest'] = false;
$renderChildrenClosure43 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper44 = $self->getViewHelper('$viewHelper44', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper44->setArguments($arguments41);
$viewHelper44->setRenderingContext($renderingContext);
$viewHelper44->setRenderChildrenClosure($renderChildrenClosure43);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output32 .= $viewHelper44->initializeArgumentsAndRender();

$output32 .= '" />
		<link rel="neos-editpreviewdata" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments45 = array();
$arguments45['action'] = 'editPreview';
$arguments45['controller'] = 'Backend\\Settings';
$arguments45['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments45['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array46 = array();
$array46['version'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configurationCacheIdentifier', $renderingContext);
$arguments45['arguments'] = $array46;
$arguments45['subpackage'] = NULL;
$arguments45['section'] = '';
$arguments45['format'] = '';
$arguments45['additionalParams'] = array (
);
$arguments45['addQueryString'] = false;
$arguments45['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments45['useParentRequest'] = false;
$renderChildrenClosure47 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper48 = $self->getViewHelper('$viewHelper48', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper48->setArguments($arguments45);
$viewHelper48->setRenderingContext($renderingContext);
$viewHelper48->setRenderChildrenClosure($renderChildrenClosure47);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output32 .= $viewHelper48->initializeArgumentsAndRender();

$output32 .= '" />
		<link rel="neos-service-contentdimensions" type="application/vnd.typo3.neos.contentdimensions" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments49 = array();
$arguments49['action'] = 'index';
$arguments49['controller'] = 'Service\\ContentDimensions';
$arguments49['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments49['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
// Rendering Array
$array50 = array();
$array50['version'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'configurationCacheIdentifier', $renderingContext);
$arguments49['arguments'] = $array50;
$arguments49['subpackage'] = NULL;
$arguments49['section'] = '';
$arguments49['format'] = '';
$arguments49['additionalParams'] = array (
);
$arguments49['addQueryString'] = false;
$arguments49['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments49['useParentRequest'] = false;
$renderChildrenClosure51 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper52 = $self->getViewHelper('$viewHelper52', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper52->setArguments($arguments49);
$viewHelper52->setRenderingContext($renderingContext);
$viewHelper52->setRenderChildrenClosure($renderChildrenClosure51);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output32 .= $viewHelper52->initializeArgumentsAndRender();

$output32 .= '" />
	';
return $output32;
};
$viewHelper53 = $self->getViewHelper('$viewHelper53', $renderingContext, 'TYPO3\Fluid\ViewHelpers\AliasViewHelper');
$viewHelper53->setArguments($arguments26);
$viewHelper53->setRenderingContext($renderingContext);
$viewHelper53->setRenderChildrenClosure($renderChildrenClosure31);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\AliasViewHelper

$output3 .= $viewHelper53->initializeArgumentsAndRender();

$output3 .= '

	<!-- Temporary URL endpoints, will be removed / grouped when a REST interface is fully implemented -->
	<link rel="neos-service-workspace-publishNode" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments54 = array();
$arguments54['action'] = 'publishNode';
$arguments54['controller'] = 'Workspace';
$arguments54['package'] = 'TYPO3.Neos';
$arguments54['subpackage'] = 'Service';
// Rendering Boolean node
$arguments54['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments54['arguments'] = array (
);
$arguments54['section'] = '';
$arguments54['format'] = '';
$arguments54['additionalParams'] = array (
);
$arguments54['addQueryString'] = false;
$arguments54['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments54['useParentRequest'] = false;
$renderChildrenClosure55 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper56 = $self->getViewHelper('$viewHelper56', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper56->setArguments($arguments54);
$viewHelper56->setRenderingContext($renderingContext);
$viewHelper56->setRenderChildrenClosure($renderChildrenClosure55);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper56->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-workspace-publishNodes" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments57 = array();
$arguments57['action'] = 'publishNodes';
$arguments57['controller'] = 'Workspace';
$arguments57['package'] = 'TYPO3.Neos';
$arguments57['subpackage'] = 'Service';
// Rendering Boolean node
$arguments57['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments57['arguments'] = array (
);
$arguments57['section'] = '';
$arguments57['format'] = '';
$arguments57['additionalParams'] = array (
);
$arguments57['addQueryString'] = false;
$arguments57['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments57['useParentRequest'] = false;
$renderChildrenClosure58 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper59 = $self->getViewHelper('$viewHelper59', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper59->setArguments($arguments57);
$viewHelper59->setRenderingContext($renderingContext);
$viewHelper59->setRenderChildrenClosure($renderChildrenClosure58);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper59->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-workspace-publishAll" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments60 = array();
$arguments60['action'] = 'publishAll';
$arguments60['controller'] = 'Workspace';
$arguments60['package'] = 'TYPO3.Neos';
$arguments60['subpackage'] = 'Service';
// Rendering Boolean node
$arguments60['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments60['arguments'] = array (
);
$arguments60['section'] = '';
$arguments60['format'] = '';
$arguments60['additionalParams'] = array (
);
$arguments60['addQueryString'] = false;
$arguments60['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments60['useParentRequest'] = false;
$renderChildrenClosure61 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper62 = $self->getViewHelper('$viewHelper62', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper62->setArguments($arguments60);
$viewHelper62->setRenderingContext($renderingContext);
$viewHelper62->setRenderChildrenClosure($renderChildrenClosure61);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper62->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-workspace-discardNode" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments63 = array();
$arguments63['action'] = 'discardNode';
$arguments63['controller'] = 'Workspace';
$arguments63['package'] = 'TYPO3.Neos';
$arguments63['subpackage'] = 'Service';
// Rendering Boolean node
$arguments63['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments63['arguments'] = array (
);
$arguments63['section'] = '';
$arguments63['format'] = '';
$arguments63['additionalParams'] = array (
);
$arguments63['addQueryString'] = false;
$arguments63['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments63['useParentRequest'] = false;
$renderChildrenClosure64 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper65 = $self->getViewHelper('$viewHelper65', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper65->setArguments($arguments63);
$viewHelper65->setRenderingContext($renderingContext);
$viewHelper65->setRenderChildrenClosure($renderChildrenClosure64);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper65->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-workspace-discardNodes" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments66 = array();
$arguments66['action'] = 'discardNodes';
$arguments66['controller'] = 'Workspace';
$arguments66['package'] = 'TYPO3.Neos';
$arguments66['subpackage'] = 'Service';
// Rendering Boolean node
$arguments66['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments66['arguments'] = array (
);
$arguments66['section'] = '';
$arguments66['format'] = '';
$arguments66['additionalParams'] = array (
);
$arguments66['addQueryString'] = false;
$arguments66['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments66['useParentRequest'] = false;
$renderChildrenClosure67 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper68 = $self->getViewHelper('$viewHelper68', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper68->setArguments($arguments66);
$viewHelper68->setRenderingContext($renderingContext);
$viewHelper68->setRenderChildrenClosure($renderChildrenClosure67);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper68->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-workspace-discardAll" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments69 = array();
$arguments69['action'] = 'discardAll';
$arguments69['controller'] = 'Workspace';
$arguments69['package'] = 'TYPO3.Neos';
$arguments69['subpackage'] = 'Service';
// Rendering Boolean node
$arguments69['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments69['arguments'] = array (
);
$arguments69['section'] = '';
$arguments69['format'] = '';
$arguments69['additionalParams'] = array (
);
$arguments69['addQueryString'] = false;
$arguments69['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments69['useParentRequest'] = false;
$renderChildrenClosure70 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper71 = $self->getViewHelper('$viewHelper71', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper71->setArguments($arguments69);
$viewHelper71->setRenderingContext($renderingContext);
$viewHelper71->setRenderChildrenClosure($renderChildrenClosure70);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper71->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-workspace-getWorkspaceWideUnpublishedNodes" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments72 = array();
$arguments72['action'] = 'getWorkspaceWideUnpublishedNodes';
$arguments72['controller'] = 'Workspace';
$arguments72['package'] = 'TYPO3.Neos';
$arguments72['subpackage'] = 'Service';
// Rendering Boolean node
$arguments72['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments72['arguments'] = array (
);
$arguments72['section'] = '';
$arguments72['format'] = '';
$arguments72['additionalParams'] = array (
);
$arguments72['addQueryString'] = false;
$arguments72['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments72['useParentRequest'] = false;
$renderChildrenClosure73 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper74 = $self->getViewHelper('$viewHelper74', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper74->setArguments($arguments72);
$viewHelper74->setRenderingContext($renderingContext);
$viewHelper74->setRenderChildrenClosure($renderChildrenClosure73);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper74->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-node-getChildNodesForTree" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments75 = array();
$arguments75['action'] = 'getChildNodesForTree';
$arguments75['controller'] = 'Node';
$arguments75['package'] = 'TYPO3.Neos';
$arguments75['subpackage'] = 'Service';
// Rendering Boolean node
$arguments75['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments75['arguments'] = array (
);
$arguments75['section'] = '';
$arguments75['format'] = '';
$arguments75['additionalParams'] = array (
);
$arguments75['addQueryString'] = false;
$arguments75['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments75['useParentRequest'] = false;
$renderChildrenClosure76 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper77 = $self->getViewHelper('$viewHelper77', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper77->setArguments($arguments75);
$viewHelper77->setRenderingContext($renderingContext);
$viewHelper77->setRenderChildrenClosure($renderChildrenClosure76);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper77->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-node-createNodeForTheTree" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments78 = array();
$arguments78['action'] = 'createNodeForTheTree';
$arguments78['controller'] = 'Node';
$arguments78['package'] = 'TYPO3.Neos';
$arguments78['subpackage'] = 'Service';
// Rendering Boolean node
$arguments78['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments78['arguments'] = array (
);
$arguments78['section'] = '';
$arguments78['format'] = '';
$arguments78['additionalParams'] = array (
);
$arguments78['addQueryString'] = false;
$arguments78['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments78['useParentRequest'] = false;
$renderChildrenClosure79 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper80 = $self->getViewHelper('$viewHelper80', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper80->setArguments($arguments78);
$viewHelper80->setRenderingContext($renderingContext);
$viewHelper80->setRenderChildrenClosure($renderChildrenClosure79);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper80->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-node-filterChildNodesForTree" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments81 = array();
$arguments81['action'] = 'filterChildNodesForTree';
$arguments81['controller'] = 'Node';
$arguments81['package'] = 'TYPO3.Neos';
$arguments81['subpackage'] = 'Service';
// Rendering Boolean node
$arguments81['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments81['arguments'] = array (
);
$arguments81['section'] = '';
$arguments81['format'] = '';
$arguments81['additionalParams'] = array (
);
$arguments81['addQueryString'] = false;
$arguments81['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments81['useParentRequest'] = false;
$renderChildrenClosure82 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper83 = $self->getViewHelper('$viewHelper83', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper83->setArguments($arguments81);
$viewHelper83->setRenderingContext($renderingContext);
$viewHelper83->setRenderChildrenClosure($renderChildrenClosure82);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper83->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-node-create" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments84 = array();
$arguments84['action'] = 'create';
$arguments84['controller'] = 'Node';
$arguments84['package'] = 'TYPO3.Neos';
$arguments84['subpackage'] = 'Service';
// Rendering Boolean node
$arguments84['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments84['arguments'] = array (
);
$arguments84['section'] = '';
$arguments84['format'] = '';
$arguments84['additionalParams'] = array (
);
$arguments84['addQueryString'] = false;
$arguments84['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments84['useParentRequest'] = false;
$renderChildrenClosure85 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper86 = $self->getViewHelper('$viewHelper86', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper86->setArguments($arguments84);
$viewHelper86->setRenderingContext($renderingContext);
$viewHelper86->setRenderChildrenClosure($renderChildrenClosure85);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper86->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-node-createAndRender" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments87 = array();
$arguments87['action'] = 'createAndRender';
$arguments87['controller'] = 'Node';
$arguments87['package'] = 'TYPO3.Neos';
$arguments87['subpackage'] = 'Service';
// Rendering Boolean node
$arguments87['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments87['arguments'] = array (
);
$arguments87['section'] = '';
$arguments87['format'] = '';
$arguments87['additionalParams'] = array (
);
$arguments87['addQueryString'] = false;
$arguments87['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments87['useParentRequest'] = false;
$renderChildrenClosure88 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper89 = $self->getViewHelper('$viewHelper89', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper89->setArguments($arguments87);
$viewHelper89->setRenderingContext($renderingContext);
$viewHelper89->setRenderChildrenClosure($renderChildrenClosure88);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper89->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-node-move" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments90 = array();
$arguments90['action'] = 'move';
$arguments90['controller'] = 'Node';
$arguments90['package'] = 'TYPO3.Neos';
$arguments90['subpackage'] = 'Service';
// Rendering Boolean node
$arguments90['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments90['arguments'] = array (
);
$arguments90['section'] = '';
$arguments90['format'] = '';
$arguments90['additionalParams'] = array (
);
$arguments90['addQueryString'] = false;
$arguments90['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments90['useParentRequest'] = false;
$renderChildrenClosure91 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper92 = $self->getViewHelper('$viewHelper92', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper92->setArguments($arguments90);
$viewHelper92->setRenderingContext($renderingContext);
$viewHelper92->setRenderChildrenClosure($renderChildrenClosure91);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper92->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-node-copy" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments93 = array();
$arguments93['action'] = 'copy';
$arguments93['controller'] = 'Node';
$arguments93['package'] = 'TYPO3.Neos';
$arguments93['subpackage'] = 'Service';
// Rendering Boolean node
$arguments93['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments93['arguments'] = array (
);
$arguments93['section'] = '';
$arguments93['format'] = '';
$arguments93['additionalParams'] = array (
);
$arguments93['addQueryString'] = false;
$arguments93['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments93['useParentRequest'] = false;
$renderChildrenClosure94 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper95 = $self->getViewHelper('$viewHelper95', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper95->setArguments($arguments93);
$viewHelper95->setRenderingContext($renderingContext);
$viewHelper95->setRenderChildrenClosure($renderChildrenClosure94);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper95->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-node-update" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments96 = array();
$arguments96['action'] = 'update';
$arguments96['controller'] = 'Node';
$arguments96['package'] = 'TYPO3.Neos';
$arguments96['subpackage'] = 'Service';
// Rendering Boolean node
$arguments96['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments96['arguments'] = array (
);
$arguments96['section'] = '';
$arguments96['format'] = '';
$arguments96['additionalParams'] = array (
);
$arguments96['addQueryString'] = false;
$arguments96['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments96['useParentRequest'] = false;
$renderChildrenClosure97 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper98 = $self->getViewHelper('$viewHelper98', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper98->setArguments($arguments96);
$viewHelper98->setRenderingContext($renderingContext);
$viewHelper98->setRenderChildrenClosure($renderChildrenClosure97);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper98->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-node-delete" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments99 = array();
$arguments99['action'] = 'delete';
$arguments99['controller'] = 'Node';
$arguments99['package'] = 'TYPO3.Neos';
$arguments99['subpackage'] = 'Service';
// Rendering Boolean node
$arguments99['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments99['arguments'] = array (
);
$arguments99['section'] = '';
$arguments99['format'] = '';
$arguments99['additionalParams'] = array (
);
$arguments99['addQueryString'] = false;
$arguments99['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments99['useParentRequest'] = false;
$renderChildrenClosure100 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper101 = $self->getViewHelper('$viewHelper101', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper101->setArguments($arguments99);
$viewHelper101->setRenderingContext($renderingContext);
$viewHelper101->setRenderChildrenClosure($renderChildrenClosure100);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper101->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-node-searchPage" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments102 = array();
$arguments102['action'] = 'searchPage';
$arguments102['controller'] = 'Node';
$arguments102['package'] = 'TYPO3.Neos';
$arguments102['subpackage'] = 'Service';
// Rendering Boolean node
$arguments102['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments102['arguments'] = array (
);
$arguments102['section'] = '';
$arguments102['format'] = '';
$arguments102['additionalParams'] = array (
);
$arguments102['addQueryString'] = false;
$arguments102['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments102['useParentRequest'] = false;
$renderChildrenClosure103 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper104 = $self->getViewHelper('$viewHelper104', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper104->setArguments($arguments102);
$viewHelper104->setRenderingContext($renderingContext);
$viewHelper104->setRenderChildrenClosure($renderChildrenClosure103);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper104->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-service-node-getPageByNodePath" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments105 = array();
$arguments105['action'] = 'getPageByNodePath';
$arguments105['controller'] = 'Node';
$arguments105['package'] = 'TYPO3.Neos';
$arguments105['subpackage'] = 'Service';
// Rendering Boolean node
$arguments105['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments105['arguments'] = array (
);
$arguments105['section'] = '';
$arguments105['format'] = '';
$arguments105['additionalParams'] = array (
);
$arguments105['addQueryString'] = false;
$arguments105['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments105['useParentRequest'] = false;
$renderChildrenClosure106 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper107 = $self->getViewHelper('$viewHelper107', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper107->setArguments($arguments105);
$viewHelper107->setRenderingContext($renderingContext);
$viewHelper107->setRenderChildrenClosure($renderChildrenClosure106);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper107->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-images" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments108 = array();
$arguments108['action'] = 'imageWithMetadata';
$arguments108['controller'] = 'Backend\\Content';
$arguments108['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments108['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments108['arguments'] = array (
);
$arguments108['subpackage'] = NULL;
$arguments108['section'] = '';
$arguments108['format'] = '';
$arguments108['additionalParams'] = array (
);
$arguments108['addQueryString'] = false;
$arguments108['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments108['useParentRequest'] = false;
$renderChildrenClosure109 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper110 = $self->getViewHelper('$viewHelper110', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper110->setArguments($arguments108);
$viewHelper110->setRenderingContext($renderingContext);
$viewHelper110->setRenderChildrenClosure($renderChildrenClosure109);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper110->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-asset-upload" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments111 = array();
$arguments111['action'] = 'uploadAsset';
$arguments111['controller'] = 'Backend\\Content';
$arguments111['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments111['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments111['arguments'] = array (
);
$arguments111['subpackage'] = NULL;
$arguments111['section'] = '';
$arguments111['format'] = '';
$arguments111['additionalParams'] = array (
);
$arguments111['addQueryString'] = false;
$arguments111['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments111['useParentRequest'] = false;
$renderChildrenClosure112 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper113 = $self->getViewHelper('$viewHelper113', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper113->setArguments($arguments111);
$viewHelper113->setRenderingContext($renderingContext);
$viewHelper113->setRenderChildrenClosure($renderChildrenClosure112);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper113->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-asset-metadata" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments114 = array();
$arguments114['action'] = 'assetsWithMetadata';
$arguments114['controller'] = 'Backend\\Content';
$arguments114['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments114['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments114['arguments'] = array (
);
$arguments114['subpackage'] = NULL;
$arguments114['section'] = '';
$arguments114['format'] = '';
$arguments114['additionalParams'] = array (
);
$arguments114['addQueryString'] = false;
$arguments114['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments114['useParentRequest'] = false;
$renderChildrenClosure115 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper116 = $self->getViewHelper('$viewHelper116', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper116->setArguments($arguments114);
$viewHelper116->setRenderingContext($renderingContext);
$viewHelper116->setRenderChildrenClosure($renderChildrenClosure115);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper116->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-pluginviews" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments117 = array();
$arguments117['action'] = 'pluginViews';
$arguments117['controller'] = 'Backend\\Content';
$arguments117['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments117['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments117['arguments'] = array (
);
$arguments117['subpackage'] = NULL;
$arguments117['section'] = '';
$arguments117['format'] = '';
$arguments117['additionalParams'] = array (
);
$arguments117['addQueryString'] = false;
$arguments117['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments117['useParentRequest'] = false;
$renderChildrenClosure118 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper119 = $self->getViewHelper('$viewHelper119', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper119->setArguments($arguments117);
$viewHelper119->setRenderingContext($renderingContext);
$viewHelper119->setRenderChildrenClosure($renderChildrenClosure118);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper119->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-masterplugins" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments120 = array();
$arguments120['action'] = 'masterPlugins';
$arguments120['controller'] = 'Backend\\Content';
$arguments120['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments120['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments120['arguments'] = array (
);
$arguments120['subpackage'] = NULL;
$arguments120['section'] = '';
$arguments120['format'] = '';
$arguments120['additionalParams'] = array (
);
$arguments120['addQueryString'] = false;
$arguments120['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments120['useParentRequest'] = false;
$renderChildrenClosure121 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper122 = $self->getViewHelper('$viewHelper122', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper122->setArguments($arguments120);
$viewHelper122->setRenderingContext($renderingContext);
$viewHelper122->setRenderChildrenClosure($renderChildrenClosure121);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper122->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-user-preferences" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments123 = array();
$arguments123['action'] = 'index';
$arguments123['controller'] = 'UserPreference';
$arguments123['package'] = 'TYPO3.Neos';
$arguments123['subpackage'] = 'Service';
// Rendering Boolean node
$arguments123['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments123['arguments'] = array (
);
$arguments123['section'] = '';
$arguments123['format'] = '';
$arguments123['additionalParams'] = array (
);
$arguments123['addQueryString'] = false;
$arguments123['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments123['useParentRequest'] = false;
$renderChildrenClosure124 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper125 = $self->getViewHelper('$viewHelper125', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper125->setArguments($arguments123);
$viewHelper125->setRenderingContext($renderingContext);
$viewHelper125->setRenderChildrenClosure($renderChildrenClosure124);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper125->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-data-source" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments126 = array();
$arguments126['action'] = 'index';
$arguments126['controller'] = 'DataSource';
$arguments126['package'] = 'TYPO3.Neos';
$arguments126['subpackage'] = 'Service';
// Rendering Boolean node
$arguments126['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments126['format'] = 'json';
$arguments126['arguments'] = array (
);
$arguments126['section'] = '';
$arguments126['additionalParams'] = array (
);
$arguments126['addQueryString'] = false;
$arguments126['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments126['useParentRequest'] = false;
$renderChildrenClosure127 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper128 = $self->getViewHelper('$viewHelper128', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper128->setArguments($arguments126);
$viewHelper128->setRenderingContext($renderingContext);
$viewHelper128->setRenderChildrenClosure($renderChildrenClosure127);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper128->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-login" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments129 = array();
$arguments129['action'] = 'authenticate';
$arguments129['controller'] = 'Login';
$arguments129['package'] = 'TYPO3.Neos';
$arguments129['format'] = 'json';
// Rendering Boolean node
$arguments129['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments129['arguments'] = array (
);
$arguments129['subpackage'] = NULL;
$arguments129['section'] = '';
$arguments129['additionalParams'] = array (
);
$arguments129['addQueryString'] = false;
$arguments129['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments129['useParentRequest'] = false;
$renderChildrenClosure130 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper131 = $self->getViewHelper('$viewHelper131', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper131->setArguments($arguments129);
$viewHelper131->setRenderingContext($renderingContext);
$viewHelper131->setRenderChildrenClosure($renderChildrenClosure130);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper131->initializeArgumentsAndRender();

$output3 .= '" />
	<!-- /Temporary URL endpoints -->

	<link rel="neos-public-resources" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper
$arguments132 = array();
$arguments132['path'] = '';
$arguments132['package'] = 'TYPO3.Neos';
$arguments132['resource'] = NULL;
$arguments132['localize'] = true;
$renderChildrenClosure133 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper134 = $self->getViewHelper('$viewHelper134', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper');
$viewHelper134->setArguments($arguments132);
$viewHelper134->setRenderingContext($renderingContext);
$viewHelper134->setRenderChildrenClosure($renderChildrenClosure133);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper

$output3 .= $viewHelper134->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-module-workspacesmanagement" href="';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Uri\ModuleViewHelper
$arguments135 = array();
$arguments135['path'] = 'management/workspaces';
$arguments135['action'] = NULL;
$arguments135['arguments'] = array (
);
$arguments135['section'] = '';
$arguments135['format'] = '';
$arguments135['additionalParams'] = array (
);
$arguments135['addQueryString'] = false;
$arguments135['argumentsToBeExcludedFromQueryString'] = array (
);
$renderChildrenClosure136 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper137 = $self->getViewHelper('$viewHelper137', $renderingContext, 'TYPO3\Neos\ViewHelpers\Uri\ModuleViewHelper');
$viewHelper137->setArguments($arguments135);
$viewHelper137->setRenderingContext($renderingContext);
$viewHelper137->setRenderChildrenClosure($renderChildrenClosure136);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Uri\ModuleViewHelper

$output3 .= $viewHelper137->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-media-browser" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments138 = array();
$arguments138['action'] = 'index';
$arguments138['controller'] = 'Backend\\MediaBrowser';
$arguments138['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments138['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments138['arguments'] = array (
);
$arguments138['subpackage'] = NULL;
$arguments138['section'] = '';
$arguments138['format'] = '';
$arguments138['additionalParams'] = array (
);
$arguments138['addQueryString'] = false;
$arguments138['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments138['useParentRequest'] = false;
$renderChildrenClosure139 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper140 = $self->getViewHelper('$viewHelper140', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper140->setArguments($arguments138);
$viewHelper140->setRenderingContext($renderingContext);
$viewHelper140->setRenderChildrenClosure($renderChildrenClosure139);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper140->initializeArgumentsAndRender();

$output3 .= '" />
	<link rel="neos-image-browser" href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper
$arguments141 = array();
$arguments141['action'] = 'index';
$arguments141['controller'] = 'Backend\\ImageBrowser';
$arguments141['package'] = 'TYPO3.Neos';
// Rendering Boolean node
$arguments141['absolute'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'true', $renderingContext));
$arguments141['arguments'] = array (
);
$arguments141['subpackage'] = NULL;
$arguments141['section'] = '';
$arguments141['format'] = '';
$arguments141['additionalParams'] = array (
);
$arguments141['addQueryString'] = false;
$arguments141['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments141['useParentRequest'] = false;
$renderChildrenClosure142 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper143 = $self->getViewHelper('$viewHelper143', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper');
$viewHelper143->setArguments($arguments141);
$viewHelper143->setRenderingContext($renderingContext);
$viewHelper143->setRenderChildrenClosure($renderChildrenClosure142);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Uri\ActionViewHelper

$output3 .= $viewHelper143->initializeArgumentsAndRender();

$output3 .= '" />
';
return $output3;
};
$viewHelper144 = $self->getViewHelper('$viewHelper144', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Security\IfAccessViewHelper');
$viewHelper144->setArguments($arguments1);
$viewHelper144->setRenderingContext($renderingContext);
$viewHelper144->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Security\IfAccessViewHelper

$output0 .= $viewHelper144->initializeArgumentsAndRender();

$output0 .= '
';

return $output0;
}


}
#0             59941     