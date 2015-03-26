<?php class FluidCache_TYPO3_Neos__Module_Administration_Sites_action_index_bd0c6ce2f00777a346c7d5e5e85b1ed887d09c58 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

public function getVariableContainer() {
	// TODO
	return new \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer();
}
public function getLayoutName(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;

return 'BackendSubModule';
}
public function hasLayout() {
return TRUE;
}

/**
 * section content
 */
public function section_040f06fd774092478d450774f5ba30c5da78acc8(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '
	<table class="neos-table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Rootnode name</th>
				<th>Resource package key</th>
				<th>State</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments1 = array();
$arguments1['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'sites', $renderingContext);
$arguments1['as'] = 'site';
$arguments1['key'] = '';
$arguments1['reverse'] = false;
$arguments1['iteration'] = NULL;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
$output3 = '';

$output3 .= '
				<tr>
					<td>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments4 = array();
$arguments4['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments4['keepQuotes'] = false;
$arguments4['encoding'] = 'UTF-8';
$arguments4['doubleEncode'] = true;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
return NULL;
};
$value6 = ($arguments4['value'] !== NULL ? $arguments4['value'] : $renderChildrenClosure5());

$output3 .= !is_string($value6) && !(is_object($value6) && method_exists($value6, '__toString')) ? $value6 : htmlspecialchars($value6, ($arguments4['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments4['encoding'], $arguments4['doubleEncode']);

$output3 .= '</td>
					<td>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments7 = array();
$arguments7['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments7['keepQuotes'] = false;
$arguments7['encoding'] = 'UTF-8';
$arguments7['doubleEncode'] = true;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
return NULL;
};
$value9 = ($arguments7['value'] !== NULL ? $arguments7['value'] : $renderChildrenClosure8());

$output3 .= !is_string($value9) && !(is_object($value9) && method_exists($value9, '__toString')) ? $value9 : htmlspecialchars($value9, ($arguments7['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments7['encoding'], $arguments7['doubleEncode']);

$output3 .= '</td>
					<td>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments10 = array();
$arguments10['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.siteResourcesPackageKey', $renderingContext);
$arguments10['keepQuotes'] = false;
$arguments10['encoding'] = 'UTF-8';
$arguments10['doubleEncode'] = true;
$renderChildrenClosure11 = function() use ($renderingContext, $self) {
return NULL;
};
$value12 = ($arguments10['value'] !== NULL ? $arguments10['value'] : $renderChildrenClosure11());

$output3 .= !is_string($value12) && !(is_object($value12) && method_exists($value12, '__toString')) ? $value12 : htmlspecialchars($value12, ($arguments10['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments10['encoding'], $arguments10['doubleEncode']);

$output3 .= '</td>
					<td>
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments13 = array();
// Rendering Boolean node
$arguments13['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.online', $renderingContext));
$arguments13['then'] = NULL;
$arguments13['else'] = NULL;
$renderChildrenClosure14 = function() use ($renderingContext, $self) {
$output15 = '';

$output15 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments16 = array();
$renderChildrenClosure17 = function() use ($renderingContext, $self) {
return '
								<span class="neos-badge neos-badge-success">Active</span>
							';
};
$viewHelper18 = $self->getViewHelper('$viewHelper18', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper18->setArguments($arguments16);
$viewHelper18->setRenderingContext($renderingContext);
$viewHelper18->setRenderChildrenClosure($renderChildrenClosure17);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output15 .= $viewHelper18->initializeArgumentsAndRender();

$output15 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments19 = array();
$renderChildrenClosure20 = function() use ($renderingContext, $self) {
return '
								<span class="neos-badge neos-badge-important">Inactive</span>
							';
};
$viewHelper21 = $self->getViewHelper('$viewHelper21', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper21->setArguments($arguments19);
$viewHelper21->setRenderingContext($renderingContext);
$viewHelper21->setRenderChildrenClosure($renderChildrenClosure20);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output15 .= $viewHelper21->initializeArgumentsAndRender();

$output15 .= '
						';
return $output15;
};
$arguments13['__thenClosure'] = function() use ($renderingContext, $self) {
return '
								<span class="neos-badge neos-badge-success">Active</span>
							';
};
$arguments13['__elseClosure'] = function() use ($renderingContext, $self) {
return '
								<span class="neos-badge neos-badge-important">Inactive</span>
							';
};
$viewHelper22 = $self->getViewHelper('$viewHelper22', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper22->setArguments($arguments13);
$viewHelper22->setRenderingContext($renderingContext);
$viewHelper22->setRenderChildrenClosure($renderChildrenClosure14);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output3 .= $viewHelper22->initializeArgumentsAndRender();

$output3 .= '
					</td>
					<td class="neos-action">
						<div class="neos-pull-right">
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments23 = array();
$arguments23['action'] = 'edit';
// Rendering Array
$array24 = array();
$array24['site'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site', $renderingContext);
$arguments23['arguments'] = $array24;
$arguments23['class'] = 'neos-button neos-button-primary';
$arguments23['title'] = 'Click to edit';
$arguments23['additionalAttributes'] = NULL;
$arguments23['data'] = NULL;
$arguments23['controller'] = NULL;
$arguments23['package'] = NULL;
$arguments23['subpackage'] = NULL;
$arguments23['section'] = '';
$arguments23['format'] = '';
$arguments23['additionalParams'] = array (
);
$arguments23['addQueryString'] = false;
$arguments23['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments23['useParentRequest'] = false;
$arguments23['absolute'] = true;
$arguments23['dir'] = NULL;
$arguments23['id'] = NULL;
$arguments23['lang'] = NULL;
$arguments23['style'] = NULL;
$arguments23['accesskey'] = NULL;
$arguments23['tabindex'] = NULL;
$arguments23['onclick'] = NULL;
$arguments23['name'] = NULL;
$arguments23['rel'] = NULL;
$arguments23['rev'] = NULL;
$arguments23['target'] = NULL;
$renderChildrenClosure25 = function() use ($renderingContext, $self) {
return '
								<i class="icon-pencil icon-white"></i>
							';
};
$viewHelper26 = $self->getViewHelper('$viewHelper26', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper26->setArguments($arguments23);
$viewHelper26->setRenderingContext($renderingContext);
$viewHelper26->setRenderChildrenClosure($renderChildrenClosure25);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output3 .= $viewHelper26->initializeArgumentsAndRender();

$output3 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments27 = array();
// Rendering Boolean node
$arguments27['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.state', $renderingContext), 1);
$arguments27['then'] = NULL;
$arguments27['else'] = NULL;
$renderChildrenClosure28 = function() use ($renderingContext, $self) {
$output29 = '';

$output29 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments30 = array();
$renderChildrenClosure31 = function() use ($renderingContext, $self) {
$output32 = '';

$output32 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments33 = array();
$arguments33['action'] = 'deactivateSite';
// Rendering Array
$array34 = array();
$array34['site'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site', $renderingContext);
$arguments33['arguments'] = $array34;
$arguments33['class'] = 'neos-inline';
$arguments33['additionalAttributes'] = NULL;
$arguments33['data'] = NULL;
$arguments33['controller'] = NULL;
$arguments33['package'] = NULL;
$arguments33['subpackage'] = NULL;
$arguments33['object'] = NULL;
$arguments33['section'] = '';
$arguments33['format'] = '';
$arguments33['additionalParams'] = array (
);
$arguments33['absolute'] = false;
$arguments33['addQueryString'] = false;
$arguments33['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments33['fieldNamePrefix'] = NULL;
$arguments33['actionUri'] = NULL;
$arguments33['objectName'] = NULL;
$arguments33['useParentRequest'] = false;
$arguments33['enctype'] = NULL;
$arguments33['method'] = NULL;
$arguments33['name'] = NULL;
$arguments33['onreset'] = NULL;
$arguments33['onsubmit'] = NULL;
$arguments33['dir'] = NULL;
$arguments33['id'] = NULL;
$arguments33['lang'] = NULL;
$arguments33['style'] = NULL;
$arguments33['title'] = NULL;
$arguments33['accesskey'] = NULL;
$arguments33['tabindex'] = NULL;
$arguments33['onclick'] = NULL;
$renderChildrenClosure35 = function() use ($renderingContext, $self) {
return '
										<button class="neos-button neos-button-warning" title="Click to deactivate">
											<i class="icon-minus-sign icon-white"></i>
										</button>
									';
};
$viewHelper36 = $self->getViewHelper('$viewHelper36', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper36->setArguments($arguments33);
$viewHelper36->setRenderingContext($renderingContext);
$viewHelper36->setRenderChildrenClosure($renderChildrenClosure35);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output32 .= $viewHelper36->initializeArgumentsAndRender();

$output32 .= '
								';
return $output32;
};
$viewHelper37 = $self->getViewHelper('$viewHelper37', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper37->setArguments($arguments30);
$viewHelper37->setRenderingContext($renderingContext);
$viewHelper37->setRenderChildrenClosure($renderChildrenClosure31);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output29 .= $viewHelper37->initializeArgumentsAndRender();

$output29 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments38 = array();
$renderChildrenClosure39 = function() use ($renderingContext, $self) {
$output40 = '';

$output40 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments41 = array();
$arguments41['action'] = 'activateSite';
// Rendering Array
$array42 = array();
$array42['site'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site', $renderingContext);
$arguments41['arguments'] = $array42;
$arguments41['class'] = 'neos-inline';
$arguments41['additionalAttributes'] = NULL;
$arguments41['data'] = NULL;
$arguments41['controller'] = NULL;
$arguments41['package'] = NULL;
$arguments41['subpackage'] = NULL;
$arguments41['object'] = NULL;
$arguments41['section'] = '';
$arguments41['format'] = '';
$arguments41['additionalParams'] = array (
);
$arguments41['absolute'] = false;
$arguments41['addQueryString'] = false;
$arguments41['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments41['fieldNamePrefix'] = NULL;
$arguments41['actionUri'] = NULL;
$arguments41['objectName'] = NULL;
$arguments41['useParentRequest'] = false;
$arguments41['enctype'] = NULL;
$arguments41['method'] = NULL;
$arguments41['name'] = NULL;
$arguments41['onreset'] = NULL;
$arguments41['onsubmit'] = NULL;
$arguments41['dir'] = NULL;
$arguments41['id'] = NULL;
$arguments41['lang'] = NULL;
$arguments41['style'] = NULL;
$arguments41['title'] = NULL;
$arguments41['accesskey'] = NULL;
$arguments41['tabindex'] = NULL;
$arguments41['onclick'] = NULL;
$renderChildrenClosure43 = function() use ($renderingContext, $self) {
return '
										<button class="neos-button neos-button-success" title="Click to activate">
											<i class="icon-plus-sign icon-white"></i>
										</button>
									';
};
$viewHelper44 = $self->getViewHelper('$viewHelper44', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper44->setArguments($arguments41);
$viewHelper44->setRenderingContext($renderingContext);
$viewHelper44->setRenderChildrenClosure($renderChildrenClosure43);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output40 .= $viewHelper44->initializeArgumentsAndRender();

$output40 .= '
								';
return $output40;
};
$viewHelper45 = $self->getViewHelper('$viewHelper45', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper45->setArguments($arguments38);
$viewHelper45->setRenderingContext($renderingContext);
$viewHelper45->setRenderChildrenClosure($renderChildrenClosure39);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output29 .= $viewHelper45->initializeArgumentsAndRender();

$output29 .= '
							';
return $output29;
};
$arguments27['__thenClosure'] = function() use ($renderingContext, $self) {
$output46 = '';

$output46 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments47 = array();
$arguments47['action'] = 'deactivateSite';
// Rendering Array
$array48 = array();
$array48['site'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site', $renderingContext);
$arguments47['arguments'] = $array48;
$arguments47['class'] = 'neos-inline';
$arguments47['additionalAttributes'] = NULL;
$arguments47['data'] = NULL;
$arguments47['controller'] = NULL;
$arguments47['package'] = NULL;
$arguments47['subpackage'] = NULL;
$arguments47['object'] = NULL;
$arguments47['section'] = '';
$arguments47['format'] = '';
$arguments47['additionalParams'] = array (
);
$arguments47['absolute'] = false;
$arguments47['addQueryString'] = false;
$arguments47['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments47['fieldNamePrefix'] = NULL;
$arguments47['actionUri'] = NULL;
$arguments47['objectName'] = NULL;
$arguments47['useParentRequest'] = false;
$arguments47['enctype'] = NULL;
$arguments47['method'] = NULL;
$arguments47['name'] = NULL;
$arguments47['onreset'] = NULL;
$arguments47['onsubmit'] = NULL;
$arguments47['dir'] = NULL;
$arguments47['id'] = NULL;
$arguments47['lang'] = NULL;
$arguments47['style'] = NULL;
$arguments47['title'] = NULL;
$arguments47['accesskey'] = NULL;
$arguments47['tabindex'] = NULL;
$arguments47['onclick'] = NULL;
$renderChildrenClosure49 = function() use ($renderingContext, $self) {
return '
										<button class="neos-button neos-button-warning" title="Click to deactivate">
											<i class="icon-minus-sign icon-white"></i>
										</button>
									';
};
$viewHelper50 = $self->getViewHelper('$viewHelper50', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper50->setArguments($arguments47);
$viewHelper50->setRenderingContext($renderingContext);
$viewHelper50->setRenderChildrenClosure($renderChildrenClosure49);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output46 .= $viewHelper50->initializeArgumentsAndRender();

$output46 .= '
								';
return $output46;
};
$arguments27['__elseClosure'] = function() use ($renderingContext, $self) {
$output51 = '';

$output51 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments52 = array();
$arguments52['action'] = 'activateSite';
// Rendering Array
$array53 = array();
$array53['site'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site', $renderingContext);
$arguments52['arguments'] = $array53;
$arguments52['class'] = 'neos-inline';
$arguments52['additionalAttributes'] = NULL;
$arguments52['data'] = NULL;
$arguments52['controller'] = NULL;
$arguments52['package'] = NULL;
$arguments52['subpackage'] = NULL;
$arguments52['object'] = NULL;
$arguments52['section'] = '';
$arguments52['format'] = '';
$arguments52['additionalParams'] = array (
);
$arguments52['absolute'] = false;
$arguments52['addQueryString'] = false;
$arguments52['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments52['fieldNamePrefix'] = NULL;
$arguments52['actionUri'] = NULL;
$arguments52['objectName'] = NULL;
$arguments52['useParentRequest'] = false;
$arguments52['enctype'] = NULL;
$arguments52['method'] = NULL;
$arguments52['name'] = NULL;
$arguments52['onreset'] = NULL;
$arguments52['onsubmit'] = NULL;
$arguments52['dir'] = NULL;
$arguments52['id'] = NULL;
$arguments52['lang'] = NULL;
$arguments52['style'] = NULL;
$arguments52['title'] = NULL;
$arguments52['accesskey'] = NULL;
$arguments52['tabindex'] = NULL;
$arguments52['onclick'] = NULL;
$renderChildrenClosure54 = function() use ($renderingContext, $self) {
return '
										<button class="neos-button neos-button-success" title="Click to activate">
											<i class="icon-plus-sign icon-white"></i>
										</button>
									';
};
$viewHelper55 = $self->getViewHelper('$viewHelper55', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper55->setArguments($arguments52);
$viewHelper55->setRenderingContext($renderingContext);
$viewHelper55->setRenderChildrenClosure($renderChildrenClosure54);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output51 .= $viewHelper55->initializeArgumentsAndRender();

$output51 .= '
								';
return $output51;
};
$viewHelper56 = $self->getViewHelper('$viewHelper56', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper56->setArguments($arguments27);
$viewHelper56->setRenderingContext($renderingContext);
$viewHelper56->setRenderChildrenClosure($renderChildrenClosure28);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output3 .= $viewHelper56->initializeArgumentsAndRender();

$output3 .= '
							<button class="neos-button neos-button-danger" title="Click to delete" data-toggle="modal" href="#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments57 = array();
$arguments57['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments57['keepQuotes'] = false;
$arguments57['encoding'] = 'UTF-8';
$arguments57['doubleEncode'] = true;
$renderChildrenClosure58 = function() use ($renderingContext, $self) {
return NULL;
};
$value59 = ($arguments57['value'] !== NULL ? $arguments57['value'] : $renderChildrenClosure58());

$output3 .= !is_string($value59) && !(is_object($value59) && method_exists($value59, '__toString')) ? $value59 : htmlspecialchars($value59, ($arguments57['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments57['encoding'], $arguments57['doubleEncode']);

$output3 .= '">
								<i class="icon-trash icon-white"></i>
							</button>
							<div class="neos-hide"id="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments60 = array();
$arguments60['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments60['keepQuotes'] = false;
$arguments60['encoding'] = 'UTF-8';
$arguments60['doubleEncode'] = true;
$renderChildrenClosure61 = function() use ($renderingContext, $self) {
return NULL;
};
$value62 = ($arguments60['value'] !== NULL ? $arguments60['value'] : $renderChildrenClosure61());

$output3 .= !is_string($value62) && !(is_object($value62) && method_exists($value62, '__toString')) ? $value62 : htmlspecialchars($value62, ($arguments60['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments60['encoding'], $arguments60['doubleEncode']);

$output3 .= '">
								<div class="neos-modal">
									<div class="neos-modal-header">
										<button type="button" class="neos-close neos-button" data-dismiss="modal"></button>
										<div class="neos-header">Do you really want to delete "';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments63 = array();
$arguments63['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments63['keepQuotes'] = false;
$arguments63['encoding'] = 'UTF-8';
$arguments63['doubleEncode'] = true;
$renderChildrenClosure64 = function() use ($renderingContext, $self) {
return NULL;
};
$value65 = ($arguments63['value'] !== NULL ? $arguments63['value'] : $renderChildrenClosure64());

$output3 .= !is_string($value65) && !(is_object($value65) && method_exists($value65, '__toString')) ? $value65 : htmlspecialchars($value65, ($arguments63['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments63['encoding'], $arguments63['doubleEncode']);

$output3 .= '"? This action cannot be undone.</div>
									</div>
									<div class="neos-modal-footer">
										<a href="#" class="neos-button" data-dismiss="modal">Cancel</a>
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments66 = array();
$arguments66['action'] = 'deleteSite';
// Rendering Array
$array67 = array();
$array67['site'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site', $renderingContext);
$arguments66['arguments'] = $array67;
$arguments66['class'] = 'neos-inline';
$arguments66['additionalAttributes'] = NULL;
$arguments66['data'] = NULL;
$arguments66['controller'] = NULL;
$arguments66['package'] = NULL;
$arguments66['subpackage'] = NULL;
$arguments66['object'] = NULL;
$arguments66['section'] = '';
$arguments66['format'] = '';
$arguments66['additionalParams'] = array (
);
$arguments66['absolute'] = false;
$arguments66['addQueryString'] = false;
$arguments66['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments66['fieldNamePrefix'] = NULL;
$arguments66['actionUri'] = NULL;
$arguments66['objectName'] = NULL;
$arguments66['useParentRequest'] = false;
$arguments66['enctype'] = NULL;
$arguments66['method'] = NULL;
$arguments66['name'] = NULL;
$arguments66['onreset'] = NULL;
$arguments66['onsubmit'] = NULL;
$arguments66['dir'] = NULL;
$arguments66['id'] = NULL;
$arguments66['lang'] = NULL;
$arguments66['style'] = NULL;
$arguments66['title'] = NULL;
$arguments66['accesskey'] = NULL;
$arguments66['tabindex'] = NULL;
$arguments66['onclick'] = NULL;
$renderChildrenClosure68 = function() use ($renderingContext, $self) {
return '
											<button class="neos-button neos-button-danger" title="Delete this site">
												Yes, delete the site
											</button>
										';
};
$viewHelper69 = $self->getViewHelper('$viewHelper69', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper69->setArguments($arguments66);
$viewHelper69->setRenderingContext($renderingContext);
$viewHelper69->setRenderChildrenClosure($renderChildrenClosure68);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output3 .= $viewHelper69->initializeArgumentsAndRender();

$output3 .= '
									</div>
								</div>
								<div class="neos-modal-backdrop neos-in"></div>
							</div>
						</div>
					</td>
				</tr>
			';
return $output3;
};

$output0 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments1, $renderChildrenClosure2, $renderingContext);

$output0 .= '
		</tbody>
	</table>
	<div class="neos-footer">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments70 = array();
$arguments70['action'] = 'newSite';
$arguments70['class'] = 'neos-button neos-button-primary';
$arguments70['title'] = 'Click to add a new site';
$arguments70['additionalAttributes'] = NULL;
$arguments70['data'] = NULL;
$arguments70['arguments'] = array (
);
$arguments70['controller'] = NULL;
$arguments70['package'] = NULL;
$arguments70['subpackage'] = NULL;
$arguments70['section'] = '';
$arguments70['format'] = '';
$arguments70['additionalParams'] = array (
);
$arguments70['addQueryString'] = false;
$arguments70['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments70['useParentRequest'] = false;
$arguments70['absolute'] = true;
$arguments70['dir'] = NULL;
$arguments70['id'] = NULL;
$arguments70['lang'] = NULL;
$arguments70['style'] = NULL;
$arguments70['accesskey'] = NULL;
$arguments70['tabindex'] = NULL;
$arguments70['onclick'] = NULL;
$arguments70['name'] = NULL;
$arguments70['rel'] = NULL;
$arguments70['rev'] = NULL;
$arguments70['target'] = NULL;
$renderChildrenClosure71 = function() use ($renderingContext, $self) {
return 'Add new site';
};
$viewHelper72 = $self->getViewHelper('$viewHelper72', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper72->setArguments($arguments70);
$viewHelper72->setRenderingContext($renderingContext);
$viewHelper72->setRenderChildrenClosure($renderChildrenClosure71);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output0 .= $viewHelper72->initializeArgumentsAndRender();

$output0 .= '
	</div>
';

return $output0;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output73 = '';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper
$arguments74 = array();
$arguments74['name'] = 'BackendSubModule';
$renderChildrenClosure75 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper76 = $self->getViewHelper('$viewHelper76', $renderingContext, 'TYPO3\Fluid\ViewHelpers\LayoutViewHelper');
$viewHelper76->setArguments($arguments74);
$viewHelper76->setRenderingContext($renderingContext);
$viewHelper76->setRenderChildrenClosure($renderChildrenClosure75);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper

$output73 .= $viewHelper76->initializeArgumentsAndRender();

$output73 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments77 = array();
$arguments77['name'] = 'content';
$renderChildrenClosure78 = function() use ($renderingContext, $self) {
$output79 = '';

$output79 .= '
	<table class="neos-table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Rootnode name</th>
				<th>Resource package key</th>
				<th>State</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments80 = array();
$arguments80['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'sites', $renderingContext);
$arguments80['as'] = 'site';
$arguments80['key'] = '';
$arguments80['reverse'] = false;
$arguments80['iteration'] = NULL;
$renderChildrenClosure81 = function() use ($renderingContext, $self) {
$output82 = '';

$output82 .= '
				<tr>
					<td>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments83 = array();
$arguments83['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.name', $renderingContext);
$arguments83['keepQuotes'] = false;
$arguments83['encoding'] = 'UTF-8';
$arguments83['doubleEncode'] = true;
$renderChildrenClosure84 = function() use ($renderingContext, $self) {
return NULL;
};
$value85 = ($arguments83['value'] !== NULL ? $arguments83['value'] : $renderChildrenClosure84());

$output82 .= !is_string($value85) && !(is_object($value85) && method_exists($value85, '__toString')) ? $value85 : htmlspecialchars($value85, ($arguments83['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments83['encoding'], $arguments83['doubleEncode']);

$output82 .= '</td>
					<td>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments86 = array();
$arguments86['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments86['keepQuotes'] = false;
$arguments86['encoding'] = 'UTF-8';
$arguments86['doubleEncode'] = true;
$renderChildrenClosure87 = function() use ($renderingContext, $self) {
return NULL;
};
$value88 = ($arguments86['value'] !== NULL ? $arguments86['value'] : $renderChildrenClosure87());

$output82 .= !is_string($value88) && !(is_object($value88) && method_exists($value88, '__toString')) ? $value88 : htmlspecialchars($value88, ($arguments86['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments86['encoding'], $arguments86['doubleEncode']);

$output82 .= '</td>
					<td>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments89 = array();
$arguments89['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.siteResourcesPackageKey', $renderingContext);
$arguments89['keepQuotes'] = false;
$arguments89['encoding'] = 'UTF-8';
$arguments89['doubleEncode'] = true;
$renderChildrenClosure90 = function() use ($renderingContext, $self) {
return NULL;
};
$value91 = ($arguments89['value'] !== NULL ? $arguments89['value'] : $renderChildrenClosure90());

$output82 .= !is_string($value91) && !(is_object($value91) && method_exists($value91, '__toString')) ? $value91 : htmlspecialchars($value91, ($arguments89['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments89['encoding'], $arguments89['doubleEncode']);

$output82 .= '</td>
					<td>
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments92 = array();
// Rendering Boolean node
$arguments92['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.online', $renderingContext));
$arguments92['then'] = NULL;
$arguments92['else'] = NULL;
$renderChildrenClosure93 = function() use ($renderingContext, $self) {
$output94 = '';

$output94 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments95 = array();
$renderChildrenClosure96 = function() use ($renderingContext, $self) {
return '
								<span class="neos-badge neos-badge-success">Active</span>
							';
};
$viewHelper97 = $self->getViewHelper('$viewHelper97', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper97->setArguments($arguments95);
$viewHelper97->setRenderingContext($renderingContext);
$viewHelper97->setRenderChildrenClosure($renderChildrenClosure96);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output94 .= $viewHelper97->initializeArgumentsAndRender();

$output94 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments98 = array();
$renderChildrenClosure99 = function() use ($renderingContext, $self) {
return '
								<span class="neos-badge neos-badge-important">Inactive</span>
							';
};
$viewHelper100 = $self->getViewHelper('$viewHelper100', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper100->setArguments($arguments98);
$viewHelper100->setRenderingContext($renderingContext);
$viewHelper100->setRenderChildrenClosure($renderChildrenClosure99);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output94 .= $viewHelper100->initializeArgumentsAndRender();

$output94 .= '
						';
return $output94;
};
$arguments92['__thenClosure'] = function() use ($renderingContext, $self) {
return '
								<span class="neos-badge neos-badge-success">Active</span>
							';
};
$arguments92['__elseClosure'] = function() use ($renderingContext, $self) {
return '
								<span class="neos-badge neos-badge-important">Inactive</span>
							';
};
$viewHelper101 = $self->getViewHelper('$viewHelper101', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper101->setArguments($arguments92);
$viewHelper101->setRenderingContext($renderingContext);
$viewHelper101->setRenderChildrenClosure($renderChildrenClosure93);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output82 .= $viewHelper101->initializeArgumentsAndRender();

$output82 .= '
					</td>
					<td class="neos-action">
						<div class="neos-pull-right">
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments102 = array();
$arguments102['action'] = 'edit';
// Rendering Array
$array103 = array();
$array103['site'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site', $renderingContext);
$arguments102['arguments'] = $array103;
$arguments102['class'] = 'neos-button neos-button-primary';
$arguments102['title'] = 'Click to edit';
$arguments102['additionalAttributes'] = NULL;
$arguments102['data'] = NULL;
$arguments102['controller'] = NULL;
$arguments102['package'] = NULL;
$arguments102['subpackage'] = NULL;
$arguments102['section'] = '';
$arguments102['format'] = '';
$arguments102['additionalParams'] = array (
);
$arguments102['addQueryString'] = false;
$arguments102['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments102['useParentRequest'] = false;
$arguments102['absolute'] = true;
$arguments102['dir'] = NULL;
$arguments102['id'] = NULL;
$arguments102['lang'] = NULL;
$arguments102['style'] = NULL;
$arguments102['accesskey'] = NULL;
$arguments102['tabindex'] = NULL;
$arguments102['onclick'] = NULL;
$arguments102['name'] = NULL;
$arguments102['rel'] = NULL;
$arguments102['rev'] = NULL;
$arguments102['target'] = NULL;
$renderChildrenClosure104 = function() use ($renderingContext, $self) {
return '
								<i class="icon-pencil icon-white"></i>
							';
};
$viewHelper105 = $self->getViewHelper('$viewHelper105', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper105->setArguments($arguments102);
$viewHelper105->setRenderingContext($renderingContext);
$viewHelper105->setRenderChildrenClosure($renderChildrenClosure104);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output82 .= $viewHelper105->initializeArgumentsAndRender();

$output82 .= '
							';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments106 = array();
// Rendering Boolean node
$arguments106['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.state', $renderingContext), 1);
$arguments106['then'] = NULL;
$arguments106['else'] = NULL;
$renderChildrenClosure107 = function() use ($renderingContext, $self) {
$output108 = '';

$output108 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments109 = array();
$renderChildrenClosure110 = function() use ($renderingContext, $self) {
$output111 = '';

$output111 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments112 = array();
$arguments112['action'] = 'deactivateSite';
// Rendering Array
$array113 = array();
$array113['site'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site', $renderingContext);
$arguments112['arguments'] = $array113;
$arguments112['class'] = 'neos-inline';
$arguments112['additionalAttributes'] = NULL;
$arguments112['data'] = NULL;
$arguments112['controller'] = NULL;
$arguments112['package'] = NULL;
$arguments112['subpackage'] = NULL;
$arguments112['object'] = NULL;
$arguments112['section'] = '';
$arguments112['format'] = '';
$arguments112['additionalParams'] = array (
);
$arguments112['absolute'] = false;
$arguments112['addQueryString'] = false;
$arguments112['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments112['fieldNamePrefix'] = NULL;
$arguments112['actionUri'] = NULL;
$arguments112['objectName'] = NULL;
$arguments112['useParentRequest'] = false;
$arguments112['enctype'] = NULL;
$arguments112['method'] = NULL;
$arguments112['name'] = NULL;
$arguments112['onreset'] = NULL;
$arguments112['onsubmit'] = NULL;
$arguments112['dir'] = NULL;
$arguments112['id'] = NULL;
$arguments112['lang'] = NULL;
$arguments112['style'] = NULL;
$arguments112['title'] = NULL;
$arguments112['accesskey'] = NULL;
$arguments112['tabindex'] = NULL;
$arguments112['onclick'] = NULL;
$renderChildrenClosure114 = function() use ($renderingContext, $self) {
return '
										<button class="neos-button neos-button-warning" title="Click to deactivate">
											<i class="icon-minus-sign icon-white"></i>
										</button>
									';
};
$viewHelper115 = $self->getViewHelper('$viewHelper115', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper115->setArguments($arguments112);
$viewHelper115->setRenderingContext($renderingContext);
$viewHelper115->setRenderChildrenClosure($renderChildrenClosure114);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output111 .= $viewHelper115->initializeArgumentsAndRender();

$output111 .= '
								';
return $output111;
};
$viewHelper116 = $self->getViewHelper('$viewHelper116', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper116->setArguments($arguments109);
$viewHelper116->setRenderingContext($renderingContext);
$viewHelper116->setRenderChildrenClosure($renderChildrenClosure110);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output108 .= $viewHelper116->initializeArgumentsAndRender();

$output108 .= '
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments117 = array();
$renderChildrenClosure118 = function() use ($renderingContext, $self) {
$output119 = '';

$output119 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments120 = array();
$arguments120['action'] = 'activateSite';
// Rendering Array
$array121 = array();
$array121['site'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site', $renderingContext);
$arguments120['arguments'] = $array121;
$arguments120['class'] = 'neos-inline';
$arguments120['additionalAttributes'] = NULL;
$arguments120['data'] = NULL;
$arguments120['controller'] = NULL;
$arguments120['package'] = NULL;
$arguments120['subpackage'] = NULL;
$arguments120['object'] = NULL;
$arguments120['section'] = '';
$arguments120['format'] = '';
$arguments120['additionalParams'] = array (
);
$arguments120['absolute'] = false;
$arguments120['addQueryString'] = false;
$arguments120['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments120['fieldNamePrefix'] = NULL;
$arguments120['actionUri'] = NULL;
$arguments120['objectName'] = NULL;
$arguments120['useParentRequest'] = false;
$arguments120['enctype'] = NULL;
$arguments120['method'] = NULL;
$arguments120['name'] = NULL;
$arguments120['onreset'] = NULL;
$arguments120['onsubmit'] = NULL;
$arguments120['dir'] = NULL;
$arguments120['id'] = NULL;
$arguments120['lang'] = NULL;
$arguments120['style'] = NULL;
$arguments120['title'] = NULL;
$arguments120['accesskey'] = NULL;
$arguments120['tabindex'] = NULL;
$arguments120['onclick'] = NULL;
$renderChildrenClosure122 = function() use ($renderingContext, $self) {
return '
										<button class="neos-button neos-button-success" title="Click to activate">
											<i class="icon-plus-sign icon-white"></i>
										</button>
									';
};
$viewHelper123 = $self->getViewHelper('$viewHelper123', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper123->setArguments($arguments120);
$viewHelper123->setRenderingContext($renderingContext);
$viewHelper123->setRenderChildrenClosure($renderChildrenClosure122);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output119 .= $viewHelper123->initializeArgumentsAndRender();

$output119 .= '
								';
return $output119;
};
$viewHelper124 = $self->getViewHelper('$viewHelper124', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper124->setArguments($arguments117);
$viewHelper124->setRenderingContext($renderingContext);
$viewHelper124->setRenderChildrenClosure($renderChildrenClosure118);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output108 .= $viewHelper124->initializeArgumentsAndRender();

$output108 .= '
							';
return $output108;
};
$arguments106['__thenClosure'] = function() use ($renderingContext, $self) {
$output125 = '';

$output125 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments126 = array();
$arguments126['action'] = 'deactivateSite';
// Rendering Array
$array127 = array();
$array127['site'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site', $renderingContext);
$arguments126['arguments'] = $array127;
$arguments126['class'] = 'neos-inline';
$arguments126['additionalAttributes'] = NULL;
$arguments126['data'] = NULL;
$arguments126['controller'] = NULL;
$arguments126['package'] = NULL;
$arguments126['subpackage'] = NULL;
$arguments126['object'] = NULL;
$arguments126['section'] = '';
$arguments126['format'] = '';
$arguments126['additionalParams'] = array (
);
$arguments126['absolute'] = false;
$arguments126['addQueryString'] = false;
$arguments126['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments126['fieldNamePrefix'] = NULL;
$arguments126['actionUri'] = NULL;
$arguments126['objectName'] = NULL;
$arguments126['useParentRequest'] = false;
$arguments126['enctype'] = NULL;
$arguments126['method'] = NULL;
$arguments126['name'] = NULL;
$arguments126['onreset'] = NULL;
$arguments126['onsubmit'] = NULL;
$arguments126['dir'] = NULL;
$arguments126['id'] = NULL;
$arguments126['lang'] = NULL;
$arguments126['style'] = NULL;
$arguments126['title'] = NULL;
$arguments126['accesskey'] = NULL;
$arguments126['tabindex'] = NULL;
$arguments126['onclick'] = NULL;
$renderChildrenClosure128 = function() use ($renderingContext, $self) {
return '
										<button class="neos-button neos-button-warning" title="Click to deactivate">
											<i class="icon-minus-sign icon-white"></i>
										</button>
									';
};
$viewHelper129 = $self->getViewHelper('$viewHelper129', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper129->setArguments($arguments126);
$viewHelper129->setRenderingContext($renderingContext);
$viewHelper129->setRenderChildrenClosure($renderChildrenClosure128);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output125 .= $viewHelper129->initializeArgumentsAndRender();

$output125 .= '
								';
return $output125;
};
$arguments106['__elseClosure'] = function() use ($renderingContext, $self) {
$output130 = '';

$output130 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments131 = array();
$arguments131['action'] = 'activateSite';
// Rendering Array
$array132 = array();
$array132['site'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site', $renderingContext);
$arguments131['arguments'] = $array132;
$arguments131['class'] = 'neos-inline';
$arguments131['additionalAttributes'] = NULL;
$arguments131['data'] = NULL;
$arguments131['controller'] = NULL;
$arguments131['package'] = NULL;
$arguments131['subpackage'] = NULL;
$arguments131['object'] = NULL;
$arguments131['section'] = '';
$arguments131['format'] = '';
$arguments131['additionalParams'] = array (
);
$arguments131['absolute'] = false;
$arguments131['addQueryString'] = false;
$arguments131['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments131['fieldNamePrefix'] = NULL;
$arguments131['actionUri'] = NULL;
$arguments131['objectName'] = NULL;
$arguments131['useParentRequest'] = false;
$arguments131['enctype'] = NULL;
$arguments131['method'] = NULL;
$arguments131['name'] = NULL;
$arguments131['onreset'] = NULL;
$arguments131['onsubmit'] = NULL;
$arguments131['dir'] = NULL;
$arguments131['id'] = NULL;
$arguments131['lang'] = NULL;
$arguments131['style'] = NULL;
$arguments131['title'] = NULL;
$arguments131['accesskey'] = NULL;
$arguments131['tabindex'] = NULL;
$arguments131['onclick'] = NULL;
$renderChildrenClosure133 = function() use ($renderingContext, $self) {
return '
										<button class="neos-button neos-button-success" title="Click to activate">
											<i class="icon-plus-sign icon-white"></i>
										</button>
									';
};
$viewHelper134 = $self->getViewHelper('$viewHelper134', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper134->setArguments($arguments131);
$viewHelper134->setRenderingContext($renderingContext);
$viewHelper134->setRenderChildrenClosure($renderChildrenClosure133);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output130 .= $viewHelper134->initializeArgumentsAndRender();

$output130 .= '
								';
return $output130;
};
$viewHelper135 = $self->getViewHelper('$viewHelper135', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper135->setArguments($arguments106);
$viewHelper135->setRenderingContext($renderingContext);
$viewHelper135->setRenderChildrenClosure($renderChildrenClosure107);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output82 .= $viewHelper135->initializeArgumentsAndRender();

$output82 .= '
							<button class="neos-button neos-button-danger" title="Click to delete" data-toggle="modal" href="#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments136 = array();
$arguments136['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site.nodeName', $renderingContext);
$arguments136['keepQuotes'] = false;
$arguments136['encoding'] = 'UTF-8';
$arguments136['doubleEncode'] = true;
$renderChildrenClosure137 = function() use ($renderingContext, $self) {
return NULL;
};
$value138 = ($arguments136['value'] !== NULL ? $arguments136['value'] : $renderChildrenClosure137());

$output82 .= !is_string($value138) && !(is_object($value138) && method_exists($value138, '__toString')) ? $value138 : htmlspecialchars($value138, ($arguments136['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments136['encoding'], $arguments136['doubleEncode']);

$output82 .= '">
								<i class="icon-trash icon-white"></i>
							</button>
							<div class="neos-hide"id="';
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

$output82 .= !is_string($value141) && !(is_object($value141) && method_exists($value141, '__toString')) ? $value141 : htmlspecialchars($value141, ($arguments139['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments139['encoding'], $arguments139['doubleEncode']);

$output82 .= '">
								<div class="neos-modal">
									<div class="neos-modal-header">
										<button type="button" class="neos-close neos-button" data-dismiss="modal"></button>
										<div class="neos-header">Do you really want to delete "';
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

$output82 .= !is_string($value144) && !(is_object($value144) && method_exists($value144, '__toString')) ? $value144 : htmlspecialchars($value144, ($arguments142['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments142['encoding'], $arguments142['doubleEncode']);

$output82 .= '"? This action cannot be undone.</div>
									</div>
									<div class="neos-modal-footer">
										<a href="#" class="neos-button" data-dismiss="modal">Cancel</a>
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments145 = array();
$arguments145['action'] = 'deleteSite';
// Rendering Array
$array146 = array();
$array146['site'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'site', $renderingContext);
$arguments145['arguments'] = $array146;
$arguments145['class'] = 'neos-inline';
$arguments145['additionalAttributes'] = NULL;
$arguments145['data'] = NULL;
$arguments145['controller'] = NULL;
$arguments145['package'] = NULL;
$arguments145['subpackage'] = NULL;
$arguments145['object'] = NULL;
$arguments145['section'] = '';
$arguments145['format'] = '';
$arguments145['additionalParams'] = array (
);
$arguments145['absolute'] = false;
$arguments145['addQueryString'] = false;
$arguments145['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments145['fieldNamePrefix'] = NULL;
$arguments145['actionUri'] = NULL;
$arguments145['objectName'] = NULL;
$arguments145['useParentRequest'] = false;
$arguments145['enctype'] = NULL;
$arguments145['method'] = NULL;
$arguments145['name'] = NULL;
$arguments145['onreset'] = NULL;
$arguments145['onsubmit'] = NULL;
$arguments145['dir'] = NULL;
$arguments145['id'] = NULL;
$arguments145['lang'] = NULL;
$arguments145['style'] = NULL;
$arguments145['title'] = NULL;
$arguments145['accesskey'] = NULL;
$arguments145['tabindex'] = NULL;
$arguments145['onclick'] = NULL;
$renderChildrenClosure147 = function() use ($renderingContext, $self) {
return '
											<button class="neos-button neos-button-danger" title="Delete this site">
												Yes, delete the site
											</button>
										';
};
$viewHelper148 = $self->getViewHelper('$viewHelper148', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper148->setArguments($arguments145);
$viewHelper148->setRenderingContext($renderingContext);
$viewHelper148->setRenderChildrenClosure($renderChildrenClosure147);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output82 .= $viewHelper148->initializeArgumentsAndRender();

$output82 .= '
									</div>
								</div>
								<div class="neos-modal-backdrop neos-in"></div>
							</div>
						</div>
					</td>
				</tr>
			';
return $output82;
};

$output79 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments80, $renderChildrenClosure81, $renderingContext);

$output79 .= '
		</tbody>
	</table>
	<div class="neos-footer">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments149 = array();
$arguments149['action'] = 'newSite';
$arguments149['class'] = 'neos-button neos-button-primary';
$arguments149['title'] = 'Click to add a new site';
$arguments149['additionalAttributes'] = NULL;
$arguments149['data'] = NULL;
$arguments149['arguments'] = array (
);
$arguments149['controller'] = NULL;
$arguments149['package'] = NULL;
$arguments149['subpackage'] = NULL;
$arguments149['section'] = '';
$arguments149['format'] = '';
$arguments149['additionalParams'] = array (
);
$arguments149['addQueryString'] = false;
$arguments149['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments149['useParentRequest'] = false;
$arguments149['absolute'] = true;
$arguments149['dir'] = NULL;
$arguments149['id'] = NULL;
$arguments149['lang'] = NULL;
$arguments149['style'] = NULL;
$arguments149['accesskey'] = NULL;
$arguments149['tabindex'] = NULL;
$arguments149['onclick'] = NULL;
$arguments149['name'] = NULL;
$arguments149['rel'] = NULL;
$arguments149['rev'] = NULL;
$arguments149['target'] = NULL;
$renderChildrenClosure150 = function() use ($renderingContext, $self) {
return 'Add new site';
};
$viewHelper151 = $self->getViewHelper('$viewHelper151', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper151->setArguments($arguments149);
$viewHelper151->setRenderingContext($renderingContext);
$viewHelper151->setRenderChildrenClosure($renderChildrenClosure150);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output79 .= $viewHelper151->initializeArgumentsAndRender();

$output79 .= '
	</div>
';
return $output79;
};

$output73 .= '';

return $output73;
}


}
#0             54667     