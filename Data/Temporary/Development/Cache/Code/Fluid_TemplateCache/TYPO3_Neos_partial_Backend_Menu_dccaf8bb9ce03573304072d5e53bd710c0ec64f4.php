<?php class FluidCache_TYPO3_Neos_partial_Backend_Menu_dccaf8bb9ce03573304072d5e53bd710c0ec64f4 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
 * section moduleMenu
 */
public function section_f5e58f59b4cf43b09094648bf94e6f9a9115a00e(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '
	<div class="neos-menu-section">
		';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper
$arguments1 = array();
$arguments1['path'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.modulePath', $renderingContext);
$output2 = '';

$output2 .= 'neos-menu-headline';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments3 = array();
// Rendering Boolean node
$arguments3['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.modulePath', $renderingContext), \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'modulePath', $renderingContext));
$arguments3['then'] = ' neos-active';
$arguments3['else'] = NULL;
$renderChildrenClosure4 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper5 = $self->getViewHelper('$viewHelper5', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper5->setArguments($arguments3);
$viewHelper5->setRenderingContext($renderingContext);
$viewHelper5->setRenderChildrenClosure($renderChildrenClosure4);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output2 .= $viewHelper5->initializeArgumentsAndRender();
$arguments1['class'] = $output2;
$arguments1['additionalAttributes'] = NULL;
$arguments1['data'] = NULL;
$arguments1['action'] = NULL;
$arguments1['arguments'] = array (
);
$arguments1['section'] = '';
$arguments1['format'] = '';
$arguments1['additionalParams'] = array (
);
$arguments1['addQueryString'] = false;
$arguments1['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments1['dir'] = NULL;
$arguments1['id'] = NULL;
$arguments1['lang'] = NULL;
$arguments1['style'] = NULL;
$arguments1['title'] = NULL;
$arguments1['accesskey'] = NULL;
$arguments1['tabindex'] = NULL;
$arguments1['onclick'] = NULL;
$arguments1['name'] = NULL;
$arguments1['rel'] = NULL;
$arguments1['rev'] = NULL;
$arguments1['target'] = NULL;
$renderChildrenClosure6 = function() use ($renderingContext, $self) {
$output7 = '';

$output7 .= '
			<i class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments8 = array();
// Rendering Boolean node
$arguments8['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.icon', $renderingContext));
$arguments8['then'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.icon', $renderingContext);
$arguments8['else'] = 'icon-puzzle-piece';
$renderChildrenClosure9 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper10 = $self->getViewHelper('$viewHelper10', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper10->setArguments($arguments8);
$viewHelper10->setRenderingContext($renderingContext);
$viewHelper10->setRenderChildrenClosure($renderChildrenClosure9);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output7 .= $viewHelper10->initializeArgumentsAndRender();

$output7 .= '"></i>
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments11 = array();
$arguments11['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.label', $renderingContext);
$arguments11['keepQuotes'] = false;
$arguments11['encoding'] = 'UTF-8';
$arguments11['doubleEncode'] = true;
$renderChildrenClosure12 = function() use ($renderingContext, $self) {
return NULL;
};
$value13 = ($arguments11['value'] !== NULL ? $arguments11['value'] : $renderChildrenClosure12());

$output7 .= !is_string($value13) && !(is_object($value13) && method_exists($value13, '__toString')) ? $value13 : htmlspecialchars($value13, ($arguments11['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments11['encoding'], $arguments11['doubleEncode']);

$output7 .= '
		';
return $output7;
};
$viewHelper14 = $self->getViewHelper('$viewHelper14', $renderingContext, 'TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper');
$viewHelper14->setArguments($arguments1);
$viewHelper14->setRenderingContext($renderingContext);
$viewHelper14->setRenderChildrenClosure($renderChildrenClosure6);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper

$output0 .= $viewHelper14->initializeArgumentsAndRender();

$output0 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments15 = array();
// Rendering Boolean node
$arguments15['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.submodules', $renderingContext));
$arguments15['then'] = NULL;
$arguments15['else'] = NULL;
$renderChildrenClosure16 = function() use ($renderingContext, $self) {
$output17 = '';

$output17 .= '
			<div class="neos-menu-list">
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments18 = array();
$arguments18['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.submodules', $renderingContext);
$arguments18['as'] = 'submodule';
$arguments18['key'] = '';
$arguments18['reverse'] = false;
$arguments18['iteration'] = NULL;
$renderChildrenClosure19 = function() use ($renderingContext, $self) {
$output20 = '';

$output20 .= '
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments21 = array();
// Rendering Boolean node
$arguments21['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'submodule.hideInMenu', $renderingContext));
$arguments21['then'] = NULL;
$arguments21['else'] = NULL;
$renderChildrenClosure22 = function() use ($renderingContext, $self) {
$output23 = '';

$output23 .= '
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments24 = array();
$renderChildrenClosure25 = function() use ($renderingContext, $self) {
$output26 = '';

$output26 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments27 = array();
$arguments27['section'] = 'submoduleMenu';
$arguments27['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments27['partial'] = NULL;
$arguments27['optional'] = false;
$renderChildrenClosure28 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper29 = $self->getViewHelper('$viewHelper29', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper29->setArguments($arguments27);
$viewHelper29->setRenderingContext($renderingContext);
$viewHelper29->setRenderChildrenClosure($renderChildrenClosure28);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output26 .= $viewHelper29->initializeArgumentsAndRender();

$output26 .= '
						';
return $output26;
};
$viewHelper30 = $self->getViewHelper('$viewHelper30', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper30->setArguments($arguments24);
$viewHelper30->setRenderingContext($renderingContext);
$viewHelper30->setRenderChildrenClosure($renderChildrenClosure25);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output23 .= $viewHelper30->initializeArgumentsAndRender();

$output23 .= '
					';
return $output23;
};
$arguments21['__elseClosure'] = function() use ($renderingContext, $self) {
$output31 = '';

$output31 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments32 = array();
$arguments32['section'] = 'submoduleMenu';
$arguments32['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments32['partial'] = NULL;
$arguments32['optional'] = false;
$renderChildrenClosure33 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper34 = $self->getViewHelper('$viewHelper34', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper34->setArguments($arguments32);
$viewHelper34->setRenderingContext($renderingContext);
$viewHelper34->setRenderChildrenClosure($renderChildrenClosure33);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output31 .= $viewHelper34->initializeArgumentsAndRender();

$output31 .= '
						';
return $output31;
};
$viewHelper35 = $self->getViewHelper('$viewHelper35', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper35->setArguments($arguments21);
$viewHelper35->setRenderingContext($renderingContext);
$viewHelper35->setRenderChildrenClosure($renderChildrenClosure22);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output20 .= $viewHelper35->initializeArgumentsAndRender();

$output20 .= '
				';
return $output20;
};

$output17 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments18, $renderChildrenClosure19, $renderingContext);

$output17 .= '
			</div>
		';
return $output17;
};
$viewHelper36 = $self->getViewHelper('$viewHelper36', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper36->setArguments($arguments15);
$viewHelper36->setRenderingContext($renderingContext);
$viewHelper36->setRenderChildrenClosure($renderChildrenClosure16);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output0 .= $viewHelper36->initializeArgumentsAndRender();

$output0 .= '
	</div>
';

return $output0;
}
/**
 * section submoduleMenu
 */
public function section_8accdf636c223cfda421823bb877c78e4efcda9a(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output37 = '';

$output37 .= '
	';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper
$arguments38 = array();
$arguments38['path'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'submodule.modulePath', $renderingContext);
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments39 = array();
// Rendering Boolean node
$arguments39['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'submodule.modulePath', $renderingContext), \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'modulePath', $renderingContext));
$arguments39['then'] = ' neos-active';
$arguments39['else'] = NULL;
$renderChildrenClosure40 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper41 = $self->getViewHelper('$viewHelper41', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper41->setArguments($arguments39);
$viewHelper41->setRenderingContext($renderingContext);
$viewHelper41->setRenderChildrenClosure($renderChildrenClosure40);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments38['class'] = $viewHelper41->initializeArgumentsAndRender();
$arguments38['additionalAttributes'] = NULL;
$arguments38['data'] = NULL;
$arguments38['action'] = NULL;
$arguments38['arguments'] = array (
);
$arguments38['section'] = '';
$arguments38['format'] = '';
$arguments38['additionalParams'] = array (
);
$arguments38['addQueryString'] = false;
$arguments38['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments38['dir'] = NULL;
$arguments38['id'] = NULL;
$arguments38['lang'] = NULL;
$arguments38['style'] = NULL;
$arguments38['title'] = NULL;
$arguments38['accesskey'] = NULL;
$arguments38['tabindex'] = NULL;
$arguments38['onclick'] = NULL;
$arguments38['name'] = NULL;
$arguments38['rel'] = NULL;
$arguments38['rev'] = NULL;
$arguments38['target'] = NULL;
$renderChildrenClosure42 = function() use ($renderingContext, $self) {
$output43 = '';

$output43 .= '
		<i class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments44 = array();
// Rendering Boolean node
$arguments44['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'submodule.icon', $renderingContext));
$arguments44['then'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'submodule.icon', $renderingContext);
$arguments44['else'] = 'icon-puzzle-piece';
$renderChildrenClosure45 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper46 = $self->getViewHelper('$viewHelper46', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper46->setArguments($arguments44);
$viewHelper46->setRenderingContext($renderingContext);
$viewHelper46->setRenderChildrenClosure($renderChildrenClosure45);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output43 .= $viewHelper46->initializeArgumentsAndRender();

$output43 .= '"></i>
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments47 = array();
$arguments47['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'submodule.label', $renderingContext);
$arguments47['keepQuotes'] = false;
$arguments47['encoding'] = 'UTF-8';
$arguments47['doubleEncode'] = true;
$renderChildrenClosure48 = function() use ($renderingContext, $self) {
return NULL;
};
$value49 = ($arguments47['value'] !== NULL ? $arguments47['value'] : $renderChildrenClosure48());

$output43 .= !is_string($value49) && !(is_object($value49) && method_exists($value49, '__toString')) ? $value49 : htmlspecialchars($value49, ($arguments47['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments47['encoding'], $arguments47['doubleEncode']);

$output43 .= '
	';
return $output43;
};
$viewHelper50 = $self->getViewHelper('$viewHelper50', $renderingContext, 'TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper');
$viewHelper50->setArguments($arguments38);
$viewHelper50->setRenderingContext($renderingContext);
$viewHelper50->setRenderChildrenClosure($renderChildrenClosure42);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper

$output37 .= $viewHelper50->initializeArgumentsAndRender();

$output37 .= '
';

return $output37;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output51 = '';

$output51 .= '
<noscript>
	<div id="neos-menu-panel" class="neos-noscript">
		<div class="neos-menu-section">
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments52 = array();
$arguments52['package'] = 'TYPO3.Neos';
$arguments52['controller'] = 'Backend\\Backend';
$arguments52['action'] = 'index';
$arguments52['title'] = 'Content view';
$arguments52['class'] = 'neos-menu-headline';
$arguments52['additionalAttributes'] = NULL;
$arguments52['data'] = NULL;
$arguments52['arguments'] = array (
);
$arguments52['subpackage'] = NULL;
$arguments52['section'] = '';
$arguments52['format'] = '';
$arguments52['additionalParams'] = array (
);
$arguments52['addQueryString'] = false;
$arguments52['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments52['useParentRequest'] = false;
$arguments52['absolute'] = true;
$arguments52['dir'] = NULL;
$arguments52['id'] = NULL;
$arguments52['lang'] = NULL;
$arguments52['style'] = NULL;
$arguments52['accesskey'] = NULL;
$arguments52['tabindex'] = NULL;
$arguments52['onclick'] = NULL;
$arguments52['name'] = NULL;
$arguments52['rel'] = NULL;
$arguments52['rev'] = NULL;
$arguments52['target'] = NULL;
$renderChildrenClosure53 = function() use ($renderingContext, $self) {
return '
				<i class="icon-file"></i> Content
			';
};
$viewHelper54 = $self->getViewHelper('$viewHelper54', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper54->setArguments($arguments52);
$viewHelper54->setRenderingContext($renderingContext);
$viewHelper54->setRenderChildrenClosure($renderChildrenClosure53);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output51 .= $viewHelper54->initializeArgumentsAndRender();

$output51 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments55 = array();
// Rendering Boolean node
$arguments55['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'sites', $renderingContext));
$arguments55['then'] = NULL;
$arguments55['else'] = NULL;
$renderChildrenClosure56 = function() use ($renderingContext, $self) {
$output57 = '';

$output57 .= '
				<div class="neos-menu-list">
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments58 = array();
$arguments58['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'sites', $renderingContext);
$arguments58['as'] = 'site';
$arguments58['key'] = '';
$arguments58['reverse'] = false;
$arguments58['iteration'] = NULL;
$renderChildrenClosure59 = function() use ($renderingContext, $self) {
$output60 = '';

$output60 .= '
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments61 = array();
// Rendering Boolean node
$arguments61['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.uri', $renderingContext));
$arguments61['then'] = NULL;
$arguments61['else'] = NULL;
$renderChildrenClosure62 = function() use ($renderingContext, $self) {
$output63 = '';

$output63 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments64 = array();
$renderChildrenClosure65 = function() use ($renderingContext, $self) {
$output66 = '';

$output66 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments67 = array();
// Rendering Boolean node
$arguments67['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'modulePath', $renderingContext));
$arguments67['then'] = NULL;
$arguments67['else'] = NULL;
$renderChildrenClosure68 = function() use ($renderingContext, $self) {
$output69 = '';

$output69 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments70 = array();
$renderChildrenClosure71 = function() use ($renderingContext, $self) {
$output72 = '';

$output72 .= '
										<a href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments73 = array();
$arguments73['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.uri', $renderingContext);
$arguments73['keepQuotes'] = false;
$arguments73['encoding'] = 'UTF-8';
$arguments73['doubleEncode'] = true;
$renderChildrenClosure74 = function() use ($renderingContext, $self) {
return NULL;
};
$value75 = ($arguments73['value'] !== NULL ? $arguments73['value'] : $renderChildrenClosure74());

$output72 .= !is_string($value75) && !(is_object($value75) && method_exists($value75, '__toString')) ? $value75 : htmlspecialchars($value75, ($arguments73['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments73['encoding'], $arguments73['doubleEncode']);

$output72 .= '" title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments76 = array();
$arguments76['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments76['keepQuotes'] = false;
$arguments76['encoding'] = 'UTF-8';
$arguments76['doubleEncode'] = true;
$renderChildrenClosure77 = function() use ($renderingContext, $self) {
return NULL;
};
$value78 = ($arguments76['value'] !== NULL ? $arguments76['value'] : $renderChildrenClosure77());

$output72 .= !is_string($value78) && !(is_object($value78) && method_exists($value78, '__toString')) ? $value78 : htmlspecialchars($value78, ($arguments76['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments76['encoding'], $arguments76['doubleEncode']);

$output72 .= '">
											<i class="icon-globe"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments79 = array();
$arguments79['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments79['keepQuotes'] = false;
$arguments79['encoding'] = 'UTF-8';
$arguments79['doubleEncode'] = true;
$renderChildrenClosure80 = function() use ($renderingContext, $self) {
return NULL;
};
$value81 = ($arguments79['value'] !== NULL ? $arguments79['value'] : $renderChildrenClosure80());

$output72 .= !is_string($value81) && !(is_object($value81) && method_exists($value81, '__toString')) ? $value81 : htmlspecialchars($value81, ($arguments79['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments79['encoding'], $arguments79['doubleEncode']);

$output72 .= '
										</a>
									';
return $output72;
};
$viewHelper82 = $self->getViewHelper('$viewHelper82', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper82->setArguments($arguments70);
$viewHelper82->setRenderingContext($renderingContext);
$viewHelper82->setRenderChildrenClosure($renderChildrenClosure71);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output69 .= $viewHelper82->initializeArgumentsAndRender();

$output69 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments83 = array();
$renderChildrenClosure84 = function() use ($renderingContext, $self) {
$output85 = '';

$output85 .= '
										<a href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments86 = array();
$arguments86['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.uri', $renderingContext);
$arguments86['keepQuotes'] = false;
$arguments86['encoding'] = 'UTF-8';
$arguments86['doubleEncode'] = true;
$renderChildrenClosure87 = function() use ($renderingContext, $self) {
return NULL;
};
$value88 = ($arguments86['value'] !== NULL ? $arguments86['value'] : $renderChildrenClosure87());

$output85 .= !is_string($value88) && !(is_object($value88) && method_exists($value88, '__toString')) ? $value88 : htmlspecialchars($value88, ($arguments86['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments86['encoding'], $arguments86['doubleEncode']);

$output85 .= '" title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments89 = array();
$arguments89['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments89['keepQuotes'] = false;
$arguments89['encoding'] = 'UTF-8';
$arguments89['doubleEncode'] = true;
$renderChildrenClosure90 = function() use ($renderingContext, $self) {
return NULL;
};
$value91 = ($arguments89['value'] !== NULL ? $arguments89['value'] : $renderChildrenClosure90());

$output85 .= !is_string($value91) && !(is_object($value91) && method_exists($value91, '__toString')) ? $value91 : htmlspecialchars($value91, ($arguments89['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments89['encoding'], $arguments89['doubleEncode']);

$output85 .= '" class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments92 = array();
// Rendering Boolean node
$arguments92['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.active', $renderingContext));
$arguments92['then'] = 'neos-active';
$arguments92['else'] = NULL;
$renderChildrenClosure93 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper94 = $self->getViewHelper('$viewHelper94', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper94->setArguments($arguments92);
$viewHelper94->setRenderingContext($renderingContext);
$viewHelper94->setRenderChildrenClosure($renderChildrenClosure93);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output85 .= $viewHelper94->initializeArgumentsAndRender();
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments95 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments96 = array();
$arguments96['subject'] = NULL;
$renderChildrenClosure97 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'sites', $renderingContext);
};
$viewHelper98 = $self->getViewHelper('$viewHelper98', $renderingContext, 'TYPO3\Fluid\ViewHelpers\CountViewHelper');
$viewHelper98->setArguments($arguments96);
$viewHelper98->setRenderingContext($renderingContext);
$viewHelper98->setRenderChildrenClosure($renderChildrenClosure97);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments95['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', $viewHelper98->initializeArgumentsAndRender(), 1);
$arguments95['then'] = ' neos-active';
$arguments95['else'] = NULL;
$renderChildrenClosure99 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper100 = $self->getViewHelper('$viewHelper100', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper100->setArguments($arguments95);
$viewHelper100->setRenderingContext($renderingContext);
$viewHelper100->setRenderChildrenClosure($renderChildrenClosure99);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output85 .= $viewHelper100->initializeArgumentsAndRender();

$output85 .= '">
											<i class="icon-globe"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments101 = array();
$arguments101['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments101['keepQuotes'] = false;
$arguments101['encoding'] = 'UTF-8';
$arguments101['doubleEncode'] = true;
$renderChildrenClosure102 = function() use ($renderingContext, $self) {
return NULL;
};
$value103 = ($arguments101['value'] !== NULL ? $arguments101['value'] : $renderChildrenClosure102());

$output85 .= !is_string($value103) && !(is_object($value103) && method_exists($value103, '__toString')) ? $value103 : htmlspecialchars($value103, ($arguments101['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments101['encoding'], $arguments101['doubleEncode']);

$output85 .= '
										</a>
									';
return $output85;
};
$viewHelper104 = $self->getViewHelper('$viewHelper104', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper104->setArguments($arguments83);
$viewHelper104->setRenderingContext($renderingContext);
$viewHelper104->setRenderChildrenClosure($renderChildrenClosure84);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output69 .= $viewHelper104->initializeArgumentsAndRender();

$output69 .= '
								';
return $output69;
};
$arguments67['__thenClosure'] = function() use ($renderingContext, $self) {
$output105 = '';

$output105 .= '
										<a href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments106 = array();
$arguments106['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.uri', $renderingContext);
$arguments106['keepQuotes'] = false;
$arguments106['encoding'] = 'UTF-8';
$arguments106['doubleEncode'] = true;
$renderChildrenClosure107 = function() use ($renderingContext, $self) {
return NULL;
};
$value108 = ($arguments106['value'] !== NULL ? $arguments106['value'] : $renderChildrenClosure107());

$output105 .= !is_string($value108) && !(is_object($value108) && method_exists($value108, '__toString')) ? $value108 : htmlspecialchars($value108, ($arguments106['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments106['encoding'], $arguments106['doubleEncode']);

$output105 .= '" title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments109 = array();
$arguments109['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments109['keepQuotes'] = false;
$arguments109['encoding'] = 'UTF-8';
$arguments109['doubleEncode'] = true;
$renderChildrenClosure110 = function() use ($renderingContext, $self) {
return NULL;
};
$value111 = ($arguments109['value'] !== NULL ? $arguments109['value'] : $renderChildrenClosure110());

$output105 .= !is_string($value111) && !(is_object($value111) && method_exists($value111, '__toString')) ? $value111 : htmlspecialchars($value111, ($arguments109['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments109['encoding'], $arguments109['doubleEncode']);

$output105 .= '">
											<i class="icon-globe"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments112 = array();
$arguments112['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments112['keepQuotes'] = false;
$arguments112['encoding'] = 'UTF-8';
$arguments112['doubleEncode'] = true;
$renderChildrenClosure113 = function() use ($renderingContext, $self) {
return NULL;
};
$value114 = ($arguments112['value'] !== NULL ? $arguments112['value'] : $renderChildrenClosure113());

$output105 .= !is_string($value114) && !(is_object($value114) && method_exists($value114, '__toString')) ? $value114 : htmlspecialchars($value114, ($arguments112['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments112['encoding'], $arguments112['doubleEncode']);

$output105 .= '
										</a>
									';
return $output105;
};
$arguments67['__elseClosure'] = function() use ($renderingContext, $self) {
$output115 = '';

$output115 .= '
										<a href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments116 = array();
$arguments116['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.uri', $renderingContext);
$arguments116['keepQuotes'] = false;
$arguments116['encoding'] = 'UTF-8';
$arguments116['doubleEncode'] = true;
$renderChildrenClosure117 = function() use ($renderingContext, $self) {
return NULL;
};
$value118 = ($arguments116['value'] !== NULL ? $arguments116['value'] : $renderChildrenClosure117());

$output115 .= !is_string($value118) && !(is_object($value118) && method_exists($value118, '__toString')) ? $value118 : htmlspecialchars($value118, ($arguments116['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments116['encoding'], $arguments116['doubleEncode']);

$output115 .= '" title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments119 = array();
$arguments119['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments119['keepQuotes'] = false;
$arguments119['encoding'] = 'UTF-8';
$arguments119['doubleEncode'] = true;
$renderChildrenClosure120 = function() use ($renderingContext, $self) {
return NULL;
};
$value121 = ($arguments119['value'] !== NULL ? $arguments119['value'] : $renderChildrenClosure120());

$output115 .= !is_string($value121) && !(is_object($value121) && method_exists($value121, '__toString')) ? $value121 : htmlspecialchars($value121, ($arguments119['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments119['encoding'], $arguments119['doubleEncode']);

$output115 .= '" class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments122 = array();
// Rendering Boolean node
$arguments122['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.active', $renderingContext));
$arguments122['then'] = 'neos-active';
$arguments122['else'] = NULL;
$renderChildrenClosure123 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper124 = $self->getViewHelper('$viewHelper124', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper124->setArguments($arguments122);
$viewHelper124->setRenderingContext($renderingContext);
$viewHelper124->setRenderChildrenClosure($renderChildrenClosure123);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output115 .= $viewHelper124->initializeArgumentsAndRender();
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments125 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments126 = array();
$arguments126['subject'] = NULL;
$renderChildrenClosure127 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'sites', $renderingContext);
};
$viewHelper128 = $self->getViewHelper('$viewHelper128', $renderingContext, 'TYPO3\Fluid\ViewHelpers\CountViewHelper');
$viewHelper128->setArguments($arguments126);
$viewHelper128->setRenderingContext($renderingContext);
$viewHelper128->setRenderChildrenClosure($renderChildrenClosure127);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments125['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', $viewHelper128->initializeArgumentsAndRender(), 1);
$arguments125['then'] = ' neos-active';
$arguments125['else'] = NULL;
$renderChildrenClosure129 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper130 = $self->getViewHelper('$viewHelper130', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper130->setArguments($arguments125);
$viewHelper130->setRenderingContext($renderingContext);
$viewHelper130->setRenderChildrenClosure($renderChildrenClosure129);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output115 .= $viewHelper130->initializeArgumentsAndRender();

$output115 .= '">
											<i class="icon-globe"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments131 = array();
$arguments131['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments131['keepQuotes'] = false;
$arguments131['encoding'] = 'UTF-8';
$arguments131['doubleEncode'] = true;
$renderChildrenClosure132 = function() use ($renderingContext, $self) {
return NULL;
};
$value133 = ($arguments131['value'] !== NULL ? $arguments131['value'] : $renderChildrenClosure132());

$output115 .= !is_string($value133) && !(is_object($value133) && method_exists($value133, '__toString')) ? $value133 : htmlspecialchars($value133, ($arguments131['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments131['encoding'], $arguments131['doubleEncode']);

$output115 .= '
										</a>
									';
return $output115;
};
$viewHelper134 = $self->getViewHelper('$viewHelper134', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper134->setArguments($arguments67);
$viewHelper134->setRenderingContext($renderingContext);
$viewHelper134->setRenderChildrenClosure($renderChildrenClosure68);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output66 .= $viewHelper134->initializeArgumentsAndRender();

$output66 .= '
							';
return $output66;
};
$viewHelper135 = $self->getViewHelper('$viewHelper135', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper135->setArguments($arguments64);
$viewHelper135->setRenderingContext($renderingContext);
$viewHelper135->setRenderChildrenClosure($renderChildrenClosure65);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output63 .= $viewHelper135->initializeArgumentsAndRender();

$output63 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments136 = array();
$renderChildrenClosure137 = function() use ($renderingContext, $self) {
$output138 = '';

$output138 .= '
								<span title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments139 = array();
$arguments139['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments139['keepQuotes'] = false;
$arguments139['encoding'] = 'UTF-8';
$arguments139['doubleEncode'] = true;
$renderChildrenClosure140 = function() use ($renderingContext, $self) {
return NULL;
};
$value141 = ($arguments139['value'] !== NULL ? $arguments139['value'] : $renderChildrenClosure140());

$output138 .= !is_string($value141) && !(is_object($value141) && method_exists($value141, '__toString')) ? $value141 : htmlspecialchars($value141, ($arguments139['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments139['encoding'], $arguments139['doubleEncode']);

$output138 .= '" class="neos-menu-item neos-disabled">
									<i class="icon-globe"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments142 = array();
$arguments142['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments142['keepQuotes'] = false;
$arguments142['encoding'] = 'UTF-8';
$arguments142['doubleEncode'] = true;
$renderChildrenClosure143 = function() use ($renderingContext, $self) {
return NULL;
};
$value144 = ($arguments142['value'] !== NULL ? $arguments142['value'] : $renderChildrenClosure143());

$output138 .= !is_string($value144) && !(is_object($value144) && method_exists($value144, '__toString')) ? $value144 : htmlspecialchars($value144, ($arguments142['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments142['encoding'], $arguments142['doubleEncode']);

$output138 .= '
								</span>
							';
return $output138;
};
$viewHelper145 = $self->getViewHelper('$viewHelper145', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper145->setArguments($arguments136);
$viewHelper145->setRenderingContext($renderingContext);
$viewHelper145->setRenderChildrenClosure($renderChildrenClosure137);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output63 .= $viewHelper145->initializeArgumentsAndRender();

$output63 .= '
						';
return $output63;
};
$arguments61['__thenClosure'] = function() use ($renderingContext, $self) {
$output146 = '';

$output146 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments147 = array();
// Rendering Boolean node
$arguments147['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'modulePath', $renderingContext));
$arguments147['then'] = NULL;
$arguments147['else'] = NULL;
$renderChildrenClosure148 = function() use ($renderingContext, $self) {
$output149 = '';

$output149 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments150 = array();
$renderChildrenClosure151 = function() use ($renderingContext, $self) {
$output152 = '';

$output152 .= '
										<a href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments153 = array();
$arguments153['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.uri', $renderingContext);
$arguments153['keepQuotes'] = false;
$arguments153['encoding'] = 'UTF-8';
$arguments153['doubleEncode'] = true;
$renderChildrenClosure154 = function() use ($renderingContext, $self) {
return NULL;
};
$value155 = ($arguments153['value'] !== NULL ? $arguments153['value'] : $renderChildrenClosure154());

$output152 .= !is_string($value155) && !(is_object($value155) && method_exists($value155, '__toString')) ? $value155 : htmlspecialchars($value155, ($arguments153['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments153['encoding'], $arguments153['doubleEncode']);

$output152 .= '" title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments156 = array();
$arguments156['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments156['keepQuotes'] = false;
$arguments156['encoding'] = 'UTF-8';
$arguments156['doubleEncode'] = true;
$renderChildrenClosure157 = function() use ($renderingContext, $self) {
return NULL;
};
$value158 = ($arguments156['value'] !== NULL ? $arguments156['value'] : $renderChildrenClosure157());

$output152 .= !is_string($value158) && !(is_object($value158) && method_exists($value158, '__toString')) ? $value158 : htmlspecialchars($value158, ($arguments156['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments156['encoding'], $arguments156['doubleEncode']);

$output152 .= '">
											<i class="icon-globe"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments159 = array();
$arguments159['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments159['keepQuotes'] = false;
$arguments159['encoding'] = 'UTF-8';
$arguments159['doubleEncode'] = true;
$renderChildrenClosure160 = function() use ($renderingContext, $self) {
return NULL;
};
$value161 = ($arguments159['value'] !== NULL ? $arguments159['value'] : $renderChildrenClosure160());

$output152 .= !is_string($value161) && !(is_object($value161) && method_exists($value161, '__toString')) ? $value161 : htmlspecialchars($value161, ($arguments159['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments159['encoding'], $arguments159['doubleEncode']);

$output152 .= '
										</a>
									';
return $output152;
};
$viewHelper162 = $self->getViewHelper('$viewHelper162', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper162->setArguments($arguments150);
$viewHelper162->setRenderingContext($renderingContext);
$viewHelper162->setRenderChildrenClosure($renderChildrenClosure151);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output149 .= $viewHelper162->initializeArgumentsAndRender();

$output149 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments163 = array();
$renderChildrenClosure164 = function() use ($renderingContext, $self) {
$output165 = '';

$output165 .= '
										<a href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments166 = array();
$arguments166['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.uri', $renderingContext);
$arguments166['keepQuotes'] = false;
$arguments166['encoding'] = 'UTF-8';
$arguments166['doubleEncode'] = true;
$renderChildrenClosure167 = function() use ($renderingContext, $self) {
return NULL;
};
$value168 = ($arguments166['value'] !== NULL ? $arguments166['value'] : $renderChildrenClosure167());

$output165 .= !is_string($value168) && !(is_object($value168) && method_exists($value168, '__toString')) ? $value168 : htmlspecialchars($value168, ($arguments166['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments166['encoding'], $arguments166['doubleEncode']);

$output165 .= '" title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments169 = array();
$arguments169['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments169['keepQuotes'] = false;
$arguments169['encoding'] = 'UTF-8';
$arguments169['doubleEncode'] = true;
$renderChildrenClosure170 = function() use ($renderingContext, $self) {
return NULL;
};
$value171 = ($arguments169['value'] !== NULL ? $arguments169['value'] : $renderChildrenClosure170());

$output165 .= !is_string($value171) && !(is_object($value171) && method_exists($value171, '__toString')) ? $value171 : htmlspecialchars($value171, ($arguments169['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments169['encoding'], $arguments169['doubleEncode']);

$output165 .= '" class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments172 = array();
// Rendering Boolean node
$arguments172['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.active', $renderingContext));
$arguments172['then'] = 'neos-active';
$arguments172['else'] = NULL;
$renderChildrenClosure173 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper174 = $self->getViewHelper('$viewHelper174', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper174->setArguments($arguments172);
$viewHelper174->setRenderingContext($renderingContext);
$viewHelper174->setRenderChildrenClosure($renderChildrenClosure173);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output165 .= $viewHelper174->initializeArgumentsAndRender();
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments175 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments176 = array();
$arguments176['subject'] = NULL;
$renderChildrenClosure177 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'sites', $renderingContext);
};
$viewHelper178 = $self->getViewHelper('$viewHelper178', $renderingContext, 'TYPO3\Fluid\ViewHelpers\CountViewHelper');
$viewHelper178->setArguments($arguments176);
$viewHelper178->setRenderingContext($renderingContext);
$viewHelper178->setRenderChildrenClosure($renderChildrenClosure177);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments175['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', $viewHelper178->initializeArgumentsAndRender(), 1);
$arguments175['then'] = ' neos-active';
$arguments175['else'] = NULL;
$renderChildrenClosure179 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper180 = $self->getViewHelper('$viewHelper180', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper180->setArguments($arguments175);
$viewHelper180->setRenderingContext($renderingContext);
$viewHelper180->setRenderChildrenClosure($renderChildrenClosure179);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output165 .= $viewHelper180->initializeArgumentsAndRender();

$output165 .= '">
											<i class="icon-globe"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments181 = array();
$arguments181['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments181['keepQuotes'] = false;
$arguments181['encoding'] = 'UTF-8';
$arguments181['doubleEncode'] = true;
$renderChildrenClosure182 = function() use ($renderingContext, $self) {
return NULL;
};
$value183 = ($arguments181['value'] !== NULL ? $arguments181['value'] : $renderChildrenClosure182());

$output165 .= !is_string($value183) && !(is_object($value183) && method_exists($value183, '__toString')) ? $value183 : htmlspecialchars($value183, ($arguments181['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments181['encoding'], $arguments181['doubleEncode']);

$output165 .= '
										</a>
									';
return $output165;
};
$viewHelper184 = $self->getViewHelper('$viewHelper184', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper184->setArguments($arguments163);
$viewHelper184->setRenderingContext($renderingContext);
$viewHelper184->setRenderChildrenClosure($renderChildrenClosure164);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output149 .= $viewHelper184->initializeArgumentsAndRender();

$output149 .= '
								';
return $output149;
};
$arguments147['__thenClosure'] = function() use ($renderingContext, $self) {
$output185 = '';

$output185 .= '
										<a href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments186 = array();
$arguments186['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.uri', $renderingContext);
$arguments186['keepQuotes'] = false;
$arguments186['encoding'] = 'UTF-8';
$arguments186['doubleEncode'] = true;
$renderChildrenClosure187 = function() use ($renderingContext, $self) {
return NULL;
};
$value188 = ($arguments186['value'] !== NULL ? $arguments186['value'] : $renderChildrenClosure187());

$output185 .= !is_string($value188) && !(is_object($value188) && method_exists($value188, '__toString')) ? $value188 : htmlspecialchars($value188, ($arguments186['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments186['encoding'], $arguments186['doubleEncode']);

$output185 .= '" title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments189 = array();
$arguments189['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments189['keepQuotes'] = false;
$arguments189['encoding'] = 'UTF-8';
$arguments189['doubleEncode'] = true;
$renderChildrenClosure190 = function() use ($renderingContext, $self) {
return NULL;
};
$value191 = ($arguments189['value'] !== NULL ? $arguments189['value'] : $renderChildrenClosure190());

$output185 .= !is_string($value191) && !(is_object($value191) && method_exists($value191, '__toString')) ? $value191 : htmlspecialchars($value191, ($arguments189['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments189['encoding'], $arguments189['doubleEncode']);

$output185 .= '">
											<i class="icon-globe"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments192 = array();
$arguments192['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments192['keepQuotes'] = false;
$arguments192['encoding'] = 'UTF-8';
$arguments192['doubleEncode'] = true;
$renderChildrenClosure193 = function() use ($renderingContext, $self) {
return NULL;
};
$value194 = ($arguments192['value'] !== NULL ? $arguments192['value'] : $renderChildrenClosure193());

$output185 .= !is_string($value194) && !(is_object($value194) && method_exists($value194, '__toString')) ? $value194 : htmlspecialchars($value194, ($arguments192['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments192['encoding'], $arguments192['doubleEncode']);

$output185 .= '
										</a>
									';
return $output185;
};
$arguments147['__elseClosure'] = function() use ($renderingContext, $self) {
$output195 = '';

$output195 .= '
										<a href="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments196 = array();
$arguments196['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.uri', $renderingContext);
$arguments196['keepQuotes'] = false;
$arguments196['encoding'] = 'UTF-8';
$arguments196['doubleEncode'] = true;
$renderChildrenClosure197 = function() use ($renderingContext, $self) {
return NULL;
};
$value198 = ($arguments196['value'] !== NULL ? $arguments196['value'] : $renderChildrenClosure197());

$output195 .= !is_string($value198) && !(is_object($value198) && method_exists($value198, '__toString')) ? $value198 : htmlspecialchars($value198, ($arguments196['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments196['encoding'], $arguments196['doubleEncode']);

$output195 .= '" title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments199 = array();
$arguments199['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments199['keepQuotes'] = false;
$arguments199['encoding'] = 'UTF-8';
$arguments199['doubleEncode'] = true;
$renderChildrenClosure200 = function() use ($renderingContext, $self) {
return NULL;
};
$value201 = ($arguments199['value'] !== NULL ? $arguments199['value'] : $renderChildrenClosure200());

$output195 .= !is_string($value201) && !(is_object($value201) && method_exists($value201, '__toString')) ? $value201 : htmlspecialchars($value201, ($arguments199['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments199['encoding'], $arguments199['doubleEncode']);

$output195 .= '" class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments202 = array();
// Rendering Boolean node
$arguments202['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.active', $renderingContext));
$arguments202['then'] = 'neos-active';
$arguments202['else'] = NULL;
$renderChildrenClosure203 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper204 = $self->getViewHelper('$viewHelper204', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper204->setArguments($arguments202);
$viewHelper204->setRenderingContext($renderingContext);
$viewHelper204->setRenderChildrenClosure($renderChildrenClosure203);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output195 .= $viewHelper204->initializeArgumentsAndRender();
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments205 = array();
// Rendering Boolean node
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments206 = array();
$arguments206['subject'] = NULL;
$renderChildrenClosure207 = function() use ($renderingContext, $self) {
return \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'sites', $renderingContext);
};
$viewHelper208 = $self->getViewHelper('$viewHelper208', $renderingContext, 'TYPO3\Fluid\ViewHelpers\CountViewHelper');
$viewHelper208->setArguments($arguments206);
$viewHelper208->setRenderingContext($renderingContext);
$viewHelper208->setRenderChildrenClosure($renderChildrenClosure207);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\CountViewHelper
$arguments205['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', $viewHelper208->initializeArgumentsAndRender(), 1);
$arguments205['then'] = ' neos-active';
$arguments205['else'] = NULL;
$renderChildrenClosure209 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper210 = $self->getViewHelper('$viewHelper210', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper210->setArguments($arguments205);
$viewHelper210->setRenderingContext($renderingContext);
$viewHelper210->setRenderChildrenClosure($renderChildrenClosure209);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output195 .= $viewHelper210->initializeArgumentsAndRender();

$output195 .= '">
											<i class="icon-globe"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments211 = array();
$arguments211['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments211['keepQuotes'] = false;
$arguments211['encoding'] = 'UTF-8';
$arguments211['doubleEncode'] = true;
$renderChildrenClosure212 = function() use ($renderingContext, $self) {
return NULL;
};
$value213 = ($arguments211['value'] !== NULL ? $arguments211['value'] : $renderChildrenClosure212());

$output195 .= !is_string($value213) && !(is_object($value213) && method_exists($value213, '__toString')) ? $value213 : htmlspecialchars($value213, ($arguments211['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments211['encoding'], $arguments211['doubleEncode']);

$output195 .= '
										</a>
									';
return $output195;
};
$viewHelper214 = $self->getViewHelper('$viewHelper214', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper214->setArguments($arguments147);
$viewHelper214->setRenderingContext($renderingContext);
$viewHelper214->setRenderChildrenClosure($renderChildrenClosure148);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output146 .= $viewHelper214->initializeArgumentsAndRender();

$output146 .= '
							';
return $output146;
};
$arguments61['__elseClosure'] = function() use ($renderingContext, $self) {
$output215 = '';

$output215 .= '
								<span title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments216 = array();
$arguments216['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments216['keepQuotes'] = false;
$arguments216['encoding'] = 'UTF-8';
$arguments216['doubleEncode'] = true;
$renderChildrenClosure217 = function() use ($renderingContext, $self) {
return NULL;
};
$value218 = ($arguments216['value'] !== NULL ? $arguments216['value'] : $renderChildrenClosure217());

$output215 .= !is_string($value218) && !(is_object($value218) && method_exists($value218, '__toString')) ? $value218 : htmlspecialchars($value218, ($arguments216['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments216['encoding'], $arguments216['doubleEncode']);

$output215 .= '" class="neos-menu-item neos-disabled">
									<i class="icon-globe"></i> ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments219 = array();
$arguments219['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments219['keepQuotes'] = false;
$arguments219['encoding'] = 'UTF-8';
$arguments219['doubleEncode'] = true;
$renderChildrenClosure220 = function() use ($renderingContext, $self) {
return NULL;
};
$value221 = ($arguments219['value'] !== NULL ? $arguments219['value'] : $renderChildrenClosure220());

$output215 .= !is_string($value221) && !(is_object($value221) && method_exists($value221, '__toString')) ? $value221 : htmlspecialchars($value221, ($arguments219['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments219['encoding'], $arguments219['doubleEncode']);

$output215 .= '
								</span>
							';
return $output215;
};
$viewHelper222 = $self->getViewHelper('$viewHelper222', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper222->setArguments($arguments61);
$viewHelper222->setRenderingContext($renderingContext);
$viewHelper222->setRenderChildrenClosure($renderChildrenClosure62);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output60 .= $viewHelper222->initializeArgumentsAndRender();

$output60 .= '
					';
return $output60;
};

$output57 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments58, $renderChildrenClosure59, $renderingContext);

$output57 .= '
				</div>
			';
return $output57;
};
$viewHelper223 = $self->getViewHelper('$viewHelper223', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper223->setArguments($arguments55);
$viewHelper223->setRenderingContext($renderingContext);
$viewHelper223->setRenderChildrenClosure($renderChildrenClosure56);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output51 .= $viewHelper223->initializeArgumentsAndRender();

$output51 .= '
		</div>
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments224 = array();
$arguments224['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'modules', $renderingContext);
$arguments224['as'] = 'module';
$arguments224['key'] = '';
$arguments224['reverse'] = false;
$arguments224['iteration'] = NULL;
$renderChildrenClosure225 = function() use ($renderingContext, $self) {
$output226 = '';

$output226 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments227 = array();
// Rendering Boolean node
$arguments227['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.hideInMenu', $renderingContext));
$arguments227['then'] = NULL;
$arguments227['else'] = NULL;
$renderChildrenClosure228 = function() use ($renderingContext, $self) {
$output229 = '';

$output229 .= '
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments230 = array();
$renderChildrenClosure231 = function() use ($renderingContext, $self) {
$output232 = '';

$output232 .= '
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments233 = array();
$arguments233['section'] = 'moduleMenu';
$arguments233['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments233['partial'] = NULL;
$arguments233['optional'] = false;
$renderChildrenClosure234 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper235 = $self->getViewHelper('$viewHelper235', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper235->setArguments($arguments233);
$viewHelper235->setRenderingContext($renderingContext);
$viewHelper235->setRenderChildrenClosure($renderChildrenClosure234);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output232 .= $viewHelper235->initializeArgumentsAndRender();

$output232 .= '
				';
return $output232;
};
$viewHelper236 = $self->getViewHelper('$viewHelper236', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper236->setArguments($arguments230);
$viewHelper236->setRenderingContext($renderingContext);
$viewHelper236->setRenderChildrenClosure($renderChildrenClosure231);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output229 .= $viewHelper236->initializeArgumentsAndRender();

$output229 .= '
			';
return $output229;
};
$arguments227['__elseClosure'] = function() use ($renderingContext, $self) {
$output237 = '';

$output237 .= '
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments238 = array();
$arguments238['section'] = 'moduleMenu';
$arguments238['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments238['partial'] = NULL;
$arguments238['optional'] = false;
$renderChildrenClosure239 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper240 = $self->getViewHelper('$viewHelper240', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper240->setArguments($arguments238);
$viewHelper240->setRenderingContext($renderingContext);
$viewHelper240->setRenderChildrenClosure($renderChildrenClosure239);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output237 .= $viewHelper240->initializeArgumentsAndRender();

$output237 .= '
				';
return $output237;
};
$viewHelper241 = $self->getViewHelper('$viewHelper241', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper241->setArguments($arguments227);
$viewHelper241->setRenderingContext($renderingContext);
$viewHelper241->setRenderChildrenClosure($renderChildrenClosure228);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output226 .= $viewHelper241->initializeArgumentsAndRender();

$output226 .= '
		';
return $output226;
};

$output51 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments224, $renderChildrenClosure225, $renderingContext);

$output51 .= '
	</div>
</noscript>

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments242 = array();
$arguments242['name'] = 'moduleMenu';
$renderChildrenClosure243 = function() use ($renderingContext, $self) {
$output244 = '';

$output244 .= '
	<div class="neos-menu-section">
		';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper
$arguments245 = array();
$arguments245['path'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.modulePath', $renderingContext);
$output246 = '';

$output246 .= 'neos-menu-headline';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments247 = array();
// Rendering Boolean node
$arguments247['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.modulePath', $renderingContext), \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'modulePath', $renderingContext));
$arguments247['then'] = ' neos-active';
$arguments247['else'] = NULL;
$renderChildrenClosure248 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper249 = $self->getViewHelper('$viewHelper249', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper249->setArguments($arguments247);
$viewHelper249->setRenderingContext($renderingContext);
$viewHelper249->setRenderChildrenClosure($renderChildrenClosure248);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output246 .= $viewHelper249->initializeArgumentsAndRender();
$arguments245['class'] = $output246;
$arguments245['additionalAttributes'] = NULL;
$arguments245['data'] = NULL;
$arguments245['action'] = NULL;
$arguments245['arguments'] = array (
);
$arguments245['section'] = '';
$arguments245['format'] = '';
$arguments245['additionalParams'] = array (
);
$arguments245['addQueryString'] = false;
$arguments245['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments245['dir'] = NULL;
$arguments245['id'] = NULL;
$arguments245['lang'] = NULL;
$arguments245['style'] = NULL;
$arguments245['title'] = NULL;
$arguments245['accesskey'] = NULL;
$arguments245['tabindex'] = NULL;
$arguments245['onclick'] = NULL;
$arguments245['name'] = NULL;
$arguments245['rel'] = NULL;
$arguments245['rev'] = NULL;
$arguments245['target'] = NULL;
$renderChildrenClosure250 = function() use ($renderingContext, $self) {
$output251 = '';

$output251 .= '
			<i class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments252 = array();
// Rendering Boolean node
$arguments252['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.icon', $renderingContext));
$arguments252['then'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.icon', $renderingContext);
$arguments252['else'] = 'icon-puzzle-piece';
$renderChildrenClosure253 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper254 = $self->getViewHelper('$viewHelper254', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper254->setArguments($arguments252);
$viewHelper254->setRenderingContext($renderingContext);
$viewHelper254->setRenderChildrenClosure($renderChildrenClosure253);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output251 .= $viewHelper254->initializeArgumentsAndRender();

$output251 .= '"></i>
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments255 = array();
$arguments255['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.label', $renderingContext);
$arguments255['keepQuotes'] = false;
$arguments255['encoding'] = 'UTF-8';
$arguments255['doubleEncode'] = true;
$renderChildrenClosure256 = function() use ($renderingContext, $self) {
return NULL;
};
$value257 = ($arguments255['value'] !== NULL ? $arguments255['value'] : $renderChildrenClosure256());

$output251 .= !is_string($value257) && !(is_object($value257) && method_exists($value257, '__toString')) ? $value257 : htmlspecialchars($value257, ($arguments255['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments255['encoding'], $arguments255['doubleEncode']);

$output251 .= '
		';
return $output251;
};
$viewHelper258 = $self->getViewHelper('$viewHelper258', $renderingContext, 'TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper');
$viewHelper258->setArguments($arguments245);
$viewHelper258->setRenderingContext($renderingContext);
$viewHelper258->setRenderChildrenClosure($renderChildrenClosure250);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper

$output244 .= $viewHelper258->initializeArgumentsAndRender();

$output244 .= '
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments259 = array();
// Rendering Boolean node
$arguments259['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.submodules', $renderingContext));
$arguments259['then'] = NULL;
$arguments259['else'] = NULL;
$renderChildrenClosure260 = function() use ($renderingContext, $self) {
$output261 = '';

$output261 .= '
			<div class="neos-menu-list">
				';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments262 = array();
$arguments262['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'module.submodules', $renderingContext);
$arguments262['as'] = 'submodule';
$arguments262['key'] = '';
$arguments262['reverse'] = false;
$arguments262['iteration'] = NULL;
$renderChildrenClosure263 = function() use ($renderingContext, $self) {
$output264 = '';

$output264 .= '
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments265 = array();
// Rendering Boolean node
$arguments265['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'submodule.hideInMenu', $renderingContext));
$arguments265['then'] = NULL;
$arguments265['else'] = NULL;
$renderChildrenClosure266 = function() use ($renderingContext, $self) {
$output267 = '';

$output267 .= '
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments268 = array();
$renderChildrenClosure269 = function() use ($renderingContext, $self) {
$output270 = '';

$output270 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments271 = array();
$arguments271['section'] = 'submoduleMenu';
$arguments271['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments271['partial'] = NULL;
$arguments271['optional'] = false;
$renderChildrenClosure272 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper273 = $self->getViewHelper('$viewHelper273', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper273->setArguments($arguments271);
$viewHelper273->setRenderingContext($renderingContext);
$viewHelper273->setRenderChildrenClosure($renderChildrenClosure272);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output270 .= $viewHelper273->initializeArgumentsAndRender();

$output270 .= '
						';
return $output270;
};
$viewHelper274 = $self->getViewHelper('$viewHelper274', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper274->setArguments($arguments268);
$viewHelper274->setRenderingContext($renderingContext);
$viewHelper274->setRenderChildrenClosure($renderChildrenClosure269);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output267 .= $viewHelper274->initializeArgumentsAndRender();

$output267 .= '
					';
return $output267;
};
$arguments265['__elseClosure'] = function() use ($renderingContext, $self) {
$output275 = '';

$output275 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper
$arguments276 = array();
$arguments276['section'] = 'submoduleMenu';
$arguments276['arguments'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), '_all', $renderingContext);
$arguments276['partial'] = NULL;
$arguments276['optional'] = false;
$renderChildrenClosure277 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper278 = $self->getViewHelper('$viewHelper278', $renderingContext, 'TYPO3\Fluid\ViewHelpers\RenderViewHelper');
$viewHelper278->setArguments($arguments276);
$viewHelper278->setRenderingContext($renderingContext);
$viewHelper278->setRenderChildrenClosure($renderChildrenClosure277);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\RenderViewHelper

$output275 .= $viewHelper278->initializeArgumentsAndRender();

$output275 .= '
						';
return $output275;
};
$viewHelper279 = $self->getViewHelper('$viewHelper279', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper279->setArguments($arguments265);
$viewHelper279->setRenderingContext($renderingContext);
$viewHelper279->setRenderChildrenClosure($renderChildrenClosure266);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output264 .= $viewHelper279->initializeArgumentsAndRender();

$output264 .= '
				';
return $output264;
};

$output261 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments262, $renderChildrenClosure263, $renderingContext);

$output261 .= '
			</div>
		';
return $output261;
};
$viewHelper280 = $self->getViewHelper('$viewHelper280', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper280->setArguments($arguments259);
$viewHelper280->setRenderingContext($renderingContext);
$viewHelper280->setRenderChildrenClosure($renderChildrenClosure260);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output244 .= $viewHelper280->initializeArgumentsAndRender();

$output244 .= '
	</div>
';
return $output244;
};

$output51 .= '';

$output51 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments281 = array();
$arguments281['name'] = 'submoduleMenu';
$renderChildrenClosure282 = function() use ($renderingContext, $self) {
$output283 = '';

$output283 .= '
	';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Link\ModuleViewHelper
$arguments284 = array();
$arguments284['path'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'submodule.modulePath', $renderingContext);
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments285 = array();
// Rendering Boolean node
$arguments285['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'submodule.modulePath', $renderingContext), \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'modulePath', $renderingContext));
$arguments285['then'] = ' neos-active';
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

$output289 .= '
		<i class="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments290 = array();
// Rendering Boolean node
$arguments290['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'submodule.icon', $renderingContext));
$arguments290['then'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'submodule.icon', $renderingContext);
$arguments290['else'] = 'icon-puzzle-piece';
$renderChildrenClosure291 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper292 = $self->getViewHelper('$viewHelper292', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper292->setArguments($arguments290);
$viewHelper292->setRenderingContext($renderingContext);
$viewHelper292->setRenderChildrenClosure($renderChildrenClosure291);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output289 .= $viewHelper292->initializeArgumentsAndRender();

$output289 .= '"></i>
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments293 = array();
$arguments293['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'submodule.label', $renderingContext);
$arguments293['keepQuotes'] = false;
$arguments293['encoding'] = 'UTF-8';
$arguments293['doubleEncode'] = true;
$renderChildrenClosure294 = function() use ($renderingContext, $self) {
return NULL;
};
$value295 = ($arguments293['value'] !== NULL ? $arguments293['value'] : $renderChildrenClosure294());

$output289 .= !is_string($value295) && !(is_object($value295) && method_exists($value295, '__toString')) ? $value295 : htmlspecialchars($value295, ($arguments293['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments293['encoding'], $arguments293['doubleEncode']);

$output289 .= '
	';
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
return $output283;
};

$output51 .= '';

$output51 .= '
';

return $output51;
}


}
#0             82239     