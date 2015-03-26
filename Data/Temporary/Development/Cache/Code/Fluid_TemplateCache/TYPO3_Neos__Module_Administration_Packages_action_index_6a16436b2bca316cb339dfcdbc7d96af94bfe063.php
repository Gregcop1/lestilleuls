<?php class FluidCache_TYPO3_Neos__Module_Administration_Packages_action_index_6a16436b2bca316cb339dfcdbc7d96af94bfe063 extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

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
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments1 = array();
$arguments1['action'] = 'batch';
$arguments1['additionalAttributes'] = NULL;
$arguments1['data'] = NULL;
$arguments1['arguments'] = array (
);
$arguments1['controller'] = NULL;
$arguments1['package'] = NULL;
$arguments1['subpackage'] = NULL;
$arguments1['object'] = NULL;
$arguments1['section'] = '';
$arguments1['format'] = '';
$arguments1['additionalParams'] = array (
);
$arguments1['absolute'] = false;
$arguments1['addQueryString'] = false;
$arguments1['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments1['fieldNamePrefix'] = NULL;
$arguments1['actionUri'] = NULL;
$arguments1['objectName'] = NULL;
$arguments1['useParentRequest'] = false;
$arguments1['enctype'] = NULL;
$arguments1['method'] = NULL;
$arguments1['name'] = NULL;
$arguments1['onreset'] = NULL;
$arguments1['onsubmit'] = NULL;
$arguments1['class'] = NULL;
$arguments1['dir'] = NULL;
$arguments1['id'] = NULL;
$arguments1['lang'] = NULL;
$arguments1['style'] = NULL;
$arguments1['title'] = NULL;
$arguments1['accesskey'] = NULL;
$arguments1['tabindex'] = NULL;
$arguments1['onclick'] = NULL;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
$output3 = '';

$output3 .= '
		<div class="neos-row-fluid">
			<legend>Available packages</legend>
			<br />
			<table class="neos-table">
				<thead>
					<th class="check">
						<label for="check-all" class="neos-checkbox">
							<input type="checkbox" id="check-all" /><span></span>
						</label>
					</th>
					<th>Package Name</th>
					<th>Version</th>
					<th>Package Key</th>
					<th>Package Type</th>
					<th>&nbsp;</th>
				</thead>
				<tbody>
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments4 = array();
$arguments4['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageGroups', $renderingContext);
$arguments4['key'] = 'packageGroup';
$arguments4['as'] = 'packages';
$arguments4['reverse'] = false;
$arguments4['iteration'] = NULL;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
$output6 = '';

$output6 .= '
						<tr class="neos-folder">
							<td colspan="2" class="neos-priority1">
								<strong>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments7 = array();
$arguments7['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageGroup', $renderingContext);
$arguments7['keepQuotes'] = false;
$arguments7['encoding'] = 'UTF-8';
$arguments7['doubleEncode'] = true;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
return NULL;
};
$value9 = ($arguments7['value'] !== NULL ? $arguments7['value'] : $renderChildrenClosure8());

$output6 .= !is_string($value9) && !(is_object($value9) && method_exists($value9, '__toString')) ? $value9 : htmlspecialchars($value9, ($arguments7['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments7['encoding'], $arguments7['doubleEncode']);

$output6 .= '</strong>
							</td>
							<td class="neos-priority2">&nbsp;</td>
							<td class="neos-priority3">&nbsp;</td>
							<td class="neos-priority3">&nbsp;</td>
							<td class="neos-priority1 neos-aRight">
								<i class="fold-toggle icon-chevron-up icon-white" data-toggle="fold-';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments10 = array();
$arguments10['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageGroup', $renderingContext);
$arguments10['keepQuotes'] = false;
$arguments10['encoding'] = 'UTF-8';
$arguments10['doubleEncode'] = true;
$renderChildrenClosure11 = function() use ($renderingContext, $self) {
return NULL;
};
$value12 = ($arguments10['value'] !== NULL ? $arguments10['value'] : $renderChildrenClosure11());

$output6 .= !is_string($value12) && !(is_object($value12) && method_exists($value12, '__toString')) ? $value12 : htmlspecialchars($value12, ($arguments10['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments10['encoding'], $arguments10['doubleEncode']);

$output6 .= '"></i>
							</td>
						</tr>
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments13 = array();
$arguments13['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packages', $renderingContext);
$arguments13['key'] = 'packageKey';
$arguments13['as'] = 'package';
$arguments13['reverse'] = false;
$arguments13['iteration'] = NULL;
$renderChildrenClosure14 = function() use ($renderingContext, $self) {
$output15 = '';

$output15 .= '
							<tr class="fold-';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments16 = array();
$arguments16['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageGroup', $renderingContext);
$arguments16['keepQuotes'] = false;
$arguments16['encoding'] = 'UTF-8';
$arguments16['doubleEncode'] = true;
$renderChildrenClosure17 = function() use ($renderingContext, $self) {
return NULL;
};
$value18 = ($arguments16['value'] !== NULL ? $arguments16['value'] : $renderChildrenClosure17());

$output15 .= !is_string($value18) && !(is_object($value18) && method_exists($value18, '__toString')) ? $value18 : htmlspecialchars($value18, ($arguments16['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments16['encoding'], $arguments16['doubleEncode']);
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments19 = array();
// Rendering Boolean node
$arguments19['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isActive', $renderingContext));
$arguments19['else'] = ' muted';
$arguments19['then'] = NULL;
$renderChildrenClosure20 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper21 = $self->getViewHelper('$viewHelper21', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper21->setArguments($arguments19);
$viewHelper21->setRenderingContext($renderingContext);
$viewHelper21->setRenderChildrenClosure($renderChildrenClosure20);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output15 .= $viewHelper21->initializeArgumentsAndRender();

$output15 .= '"';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments22 = array();
// Rendering Boolean node
$arguments22['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.description', $renderingContext));
$output23 = '';

$output23 .= ' title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments24 = array();
$arguments24['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.description', $renderingContext);
$arguments24['keepQuotes'] = false;
$arguments24['encoding'] = 'UTF-8';
$arguments24['doubleEncode'] = true;
$renderChildrenClosure25 = function() use ($renderingContext, $self) {
return NULL;
};
$value26 = ($arguments24['value'] !== NULL ? $arguments24['value'] : $renderChildrenClosure25());

$output23 .= !is_string($value26) && !(is_object($value26) && method_exists($value26, '__toString')) ? $value26 : htmlspecialchars($value26, ($arguments24['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments24['encoding'], $arguments24['doubleEncode']);

$output23 .= '"';
$arguments22['then'] = $output23;
$arguments22['else'] = NULL;
$renderChildrenClosure27 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper28 = $self->getViewHelper('$viewHelper28', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper28->setArguments($arguments22);
$viewHelper28->setRenderingContext($renderingContext);
$viewHelper28->setRenderChildrenClosure($renderChildrenClosure27);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output15 .= $viewHelper28->initializeArgumentsAndRender();

$output15 .= '>
								<td class="check neos-priority1">
									<label for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments29 = array();
$arguments29['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments29['keepQuotes'] = false;
$arguments29['encoding'] = 'UTF-8';
$arguments29['doubleEncode'] = true;
$renderChildrenClosure30 = function() use ($renderingContext, $self) {
return NULL;
};
$value31 = ($arguments29['value'] !== NULL ? $arguments29['value'] : $renderChildrenClosure30());

$output15 .= !is_string($value31) && !(is_object($value31) && method_exists($value31, '__toString')) ? $value31 : htmlspecialchars($value31, ($arguments29['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments29['encoding'], $arguments29['doubleEncode']);

$output15 .= '" class="neos-checkbox">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\CheckboxViewHelper
$arguments32 = array();
$arguments32['name'] = 'packageKeys[]';
$arguments32['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments32['id'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments32['additionalAttributes'] = NULL;
$arguments32['data'] = NULL;
$arguments32['checked'] = NULL;
$arguments32['multiple'] = NULL;
$arguments32['property'] = NULL;
$arguments32['disabled'] = NULL;
$arguments32['errorClass'] = 'f3-form-error';
$arguments32['class'] = NULL;
$arguments32['dir'] = NULL;
$arguments32['lang'] = NULL;
$arguments32['style'] = NULL;
$arguments32['title'] = NULL;
$arguments32['accesskey'] = NULL;
$arguments32['tabindex'] = NULL;
$arguments32['onclick'] = NULL;
$renderChildrenClosure33 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper34 = $self->getViewHelper('$viewHelper34', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\CheckboxViewHelper');
$viewHelper34->setArguments($arguments32);
$viewHelper34->setRenderingContext($renderingContext);
$viewHelper34->setRenderChildrenClosure($renderChildrenClosure33);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\CheckboxViewHelper

$output15 .= $viewHelper34->initializeArgumentsAndRender();

$output15 .= '<span></span>
									</label>
								</td>
								<td class="package-name neos-priority1">
									<label for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments35 = array();
$arguments35['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments35['keepQuotes'] = false;
$arguments35['encoding'] = 'UTF-8';
$arguments35['doubleEncode'] = true;
$renderChildrenClosure36 = function() use ($renderingContext, $self) {
return NULL;
};
$value37 = ($arguments35['value'] !== NULL ? $arguments35['value'] : $renderChildrenClosure36());

$output15 .= !is_string($value37) && !(is_object($value37) && method_exists($value37, '__toString')) ? $value37 : htmlspecialchars($value37, ($arguments35['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments35['encoding'], $arguments35['doubleEncode']);

$output15 .= '">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments38 = array();
$arguments38['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.name', $renderingContext);
$arguments38['keepQuotes'] = false;
$arguments38['encoding'] = 'UTF-8';
$arguments38['doubleEncode'] = true;
$renderChildrenClosure39 = function() use ($renderingContext, $self) {
return NULL;
};
$value40 = ($arguments38['value'] !== NULL ? $arguments38['value'] : $renderChildrenClosure39());

$output15 .= !is_string($value40) && !(is_object($value40) && method_exists($value40, '__toString')) ? $value40 : htmlspecialchars($value40, ($arguments38['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments38['encoding'], $arguments38['doubleEncode']);

$output15 .= '
									</label>
								</td>
								<td class="package-version neos-priority2">
									<label for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments41 = array();
$arguments41['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments41['keepQuotes'] = false;
$arguments41['encoding'] = 'UTF-8';
$arguments41['doubleEncode'] = true;
$renderChildrenClosure42 = function() use ($renderingContext, $self) {
return NULL;
};
$value43 = ($arguments41['value'] !== NULL ? $arguments41['value'] : $renderChildrenClosure42());

$output15 .= !is_string($value43) && !(is_object($value43) && method_exists($value43, '__toString')) ? $value43 : htmlspecialchars($value43, ($arguments41['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments41['encoding'], $arguments41['doubleEncode']);

$output15 .= '">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments44 = array();
// Rendering Boolean node
$arguments44['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.version', $renderingContext));
$arguments44['then'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.version', $renderingContext);
$arguments44['else'] = '&nbsp;';
$renderChildrenClosure45 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper46 = $self->getViewHelper('$viewHelper46', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper46->setArguments($arguments44);
$viewHelper46->setRenderingContext($renderingContext);
$viewHelper46->setRenderChildrenClosure($renderChildrenClosure45);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output15 .= $viewHelper46->initializeArgumentsAndRender();

$output15 .= '
									</label>
								</td>
								<td class="package-key neos-priority3">
									<label for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments47 = array();
$arguments47['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments47['keepQuotes'] = false;
$arguments47['encoding'] = 'UTF-8';
$arguments47['doubleEncode'] = true;
$renderChildrenClosure48 = function() use ($renderingContext, $self) {
return NULL;
};
$value49 = ($arguments47['value'] !== NULL ? $arguments47['value'] : $renderChildrenClosure48());

$output15 .= !is_string($value49) && !(is_object($value49) && method_exists($value49, '__toString')) ? $value49 : htmlspecialchars($value49, ($arguments47['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments47['encoding'], $arguments47['doubleEncode']);

$output15 .= '">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments50 = array();
$arguments50['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments50['keepQuotes'] = false;
$arguments50['encoding'] = 'UTF-8';
$arguments50['doubleEncode'] = true;
$renderChildrenClosure51 = function() use ($renderingContext, $self) {
return NULL;
};
$value52 = ($arguments50['value'] !== NULL ? $arguments50['value'] : $renderChildrenClosure51());

$output15 .= !is_string($value52) && !(is_object($value52) && method_exists($value52, '__toString')) ? $value52 : htmlspecialchars($value52, ($arguments50['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments50['encoding'], $arguments50['doubleEncode']);

$output15 .= '
									</label>
								</td>
								<td class="package-type neos-priority3">
									<label for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments53 = array();
$arguments53['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments53['keepQuotes'] = false;
$arguments53['encoding'] = 'UTF-8';
$arguments53['doubleEncode'] = true;
$renderChildrenClosure54 = function() use ($renderingContext, $self) {
return NULL;
};
$value55 = ($arguments53['value'] !== NULL ? $arguments53['value'] : $renderChildrenClosure54());

$output15 .= !is_string($value55) && !(is_object($value55) && method_exists($value55, '__toString')) ? $value55 : htmlspecialchars($value55, ($arguments53['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments53['encoding'], $arguments53['doubleEncode']);

$output15 .= '">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments56 = array();
$arguments56['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.type', $renderingContext);
$arguments56['keepQuotes'] = false;
$arguments56['encoding'] = 'UTF-8';
$arguments56['doubleEncode'] = true;
$renderChildrenClosure57 = function() use ($renderingContext, $self) {
return NULL;
};
$value58 = ($arguments56['value'] !== NULL ? $arguments56['value'] : $renderChildrenClosure57());

$output15 .= !is_string($value58) && !(is_object($value58) && method_exists($value58, '__toString')) ? $value58 : htmlspecialchars($value58, ($arguments56['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments56['encoding'], $arguments56['doubleEncode']);

$output15 .= '
									</label>
								</td>
								<td class="neos-action neos-priority1">
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments59 = array();
// Rendering Boolean node
$arguments59['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isActive', $renderingContext));
$arguments59['then'] = NULL;
$arguments59['else'] = NULL;
$renderChildrenClosure60 = function() use ($renderingContext, $self) {
$output61 = '';

$output61 .= '
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments62 = array();
$renderChildrenClosure63 = function() use ($renderingContext, $self) {
$output64 = '';

$output64 .= '
											';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments65 = array();
// Rendering Boolean node
$arguments65['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'isDevelopmentContext', $renderingContext));
$arguments65['then'] = NULL;
$arguments65['else'] = NULL;
$renderChildrenClosure66 = function() use ($renderingContext, $self) {
$output67 = '';

$output67 .= '
												';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments68 = array();
// Rendering Boolean node
$arguments68['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isFrozen', $renderingContext));
$arguments68['then'] = NULL;
$arguments68['else'] = NULL;
$renderChildrenClosure69 = function() use ($renderingContext, $self) {
$output70 = '';

$output70 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments71 = array();
$renderChildrenClosure72 = function() use ($renderingContext, $self) {
$output73 = '';

$output73 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments74 = array();
$arguments74['action'] = 'unfreeze';
$arguments74['class'] = 'neos-button neos-button-freeze neos-active';
// Rendering Array
$array75 = array();
$array75['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments74['arguments'] = $array75;
$arguments74['title'] = 'This package is currently frozen. Click here to unfreeze it.';
$arguments74['additionalAttributes'] = NULL;
$arguments74['data'] = NULL;
$arguments74['controller'] = NULL;
$arguments74['package'] = NULL;
$arguments74['subpackage'] = NULL;
$arguments74['section'] = '';
$arguments74['format'] = '';
$arguments74['additionalParams'] = array (
);
$arguments74['addQueryString'] = false;
$arguments74['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments74['useParentRequest'] = false;
$arguments74['absolute'] = true;
$arguments74['dir'] = NULL;
$arguments74['id'] = NULL;
$arguments74['lang'] = NULL;
$arguments74['style'] = NULL;
$arguments74['accesskey'] = NULL;
$arguments74['tabindex'] = NULL;
$arguments74['onclick'] = NULL;
$arguments74['name'] = NULL;
$arguments74['rel'] = NULL;
$arguments74['rev'] = NULL;
$arguments74['target'] = NULL;
$renderChildrenClosure76 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper77 = $self->getViewHelper('$viewHelper77', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper77->setArguments($arguments74);
$viewHelper77->setRenderingContext($renderingContext);
$viewHelper77->setRenderChildrenClosure($renderChildrenClosure76);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output73 .= $viewHelper77->initializeArgumentsAndRender();

$output73 .= '
													';
return $output73;
};
$viewHelper78 = $self->getViewHelper('$viewHelper78', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper78->setArguments($arguments71);
$viewHelper78->setRenderingContext($renderingContext);
$viewHelper78->setRenderChildrenClosure($renderChildrenClosure72);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output70 .= $viewHelper78->initializeArgumentsAndRender();

$output70 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments79 = array();
$renderChildrenClosure80 = function() use ($renderingContext, $self) {
$output81 = '';

$output81 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments82 = array();
$arguments82['action'] = 'freeze';
$arguments82['class'] = 'neos-button neos-button-freeze';
// Rendering Array
$array83 = array();
$array83['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments82['arguments'] = $array83;
$arguments82['title'] = 'Click here to freeze the package in order to speed up your website.';
$arguments82['additionalAttributes'] = NULL;
$arguments82['data'] = NULL;
$arguments82['controller'] = NULL;
$arguments82['package'] = NULL;
$arguments82['subpackage'] = NULL;
$arguments82['section'] = '';
$arguments82['format'] = '';
$arguments82['additionalParams'] = array (
);
$arguments82['addQueryString'] = false;
$arguments82['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments82['useParentRequest'] = false;
$arguments82['absolute'] = true;
$arguments82['dir'] = NULL;
$arguments82['id'] = NULL;
$arguments82['lang'] = NULL;
$arguments82['style'] = NULL;
$arguments82['accesskey'] = NULL;
$arguments82['tabindex'] = NULL;
$arguments82['onclick'] = NULL;
$arguments82['name'] = NULL;
$arguments82['rel'] = NULL;
$arguments82['rev'] = NULL;
$arguments82['target'] = NULL;
$renderChildrenClosure84 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper85 = $self->getViewHelper('$viewHelper85', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper85->setArguments($arguments82);
$viewHelper85->setRenderingContext($renderingContext);
$viewHelper85->setRenderChildrenClosure($renderChildrenClosure84);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output81 .= $viewHelper85->initializeArgumentsAndRender();

$output81 .= '
													';
return $output81;
};
$viewHelper86 = $self->getViewHelper('$viewHelper86', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper86->setArguments($arguments79);
$viewHelper86->setRenderingContext($renderingContext);
$viewHelper86->setRenderChildrenClosure($renderChildrenClosure80);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output70 .= $viewHelper86->initializeArgumentsAndRender();

$output70 .= '
												';
return $output70;
};
$arguments68['__thenClosure'] = function() use ($renderingContext, $self) {
$output87 = '';

$output87 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments88 = array();
$arguments88['action'] = 'unfreeze';
$arguments88['class'] = 'neos-button neos-button-freeze neos-active';
// Rendering Array
$array89 = array();
$array89['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments88['arguments'] = $array89;
$arguments88['title'] = 'This package is currently frozen. Click here to unfreeze it.';
$arguments88['additionalAttributes'] = NULL;
$arguments88['data'] = NULL;
$arguments88['controller'] = NULL;
$arguments88['package'] = NULL;
$arguments88['subpackage'] = NULL;
$arguments88['section'] = '';
$arguments88['format'] = '';
$arguments88['additionalParams'] = array (
);
$arguments88['addQueryString'] = false;
$arguments88['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments88['useParentRequest'] = false;
$arguments88['absolute'] = true;
$arguments88['dir'] = NULL;
$arguments88['id'] = NULL;
$arguments88['lang'] = NULL;
$arguments88['style'] = NULL;
$arguments88['accesskey'] = NULL;
$arguments88['tabindex'] = NULL;
$arguments88['onclick'] = NULL;
$arguments88['name'] = NULL;
$arguments88['rel'] = NULL;
$arguments88['rev'] = NULL;
$arguments88['target'] = NULL;
$renderChildrenClosure90 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper91 = $self->getViewHelper('$viewHelper91', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper91->setArguments($arguments88);
$viewHelper91->setRenderingContext($renderingContext);
$viewHelper91->setRenderChildrenClosure($renderChildrenClosure90);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output87 .= $viewHelper91->initializeArgumentsAndRender();

$output87 .= '
													';
return $output87;
};
$arguments68['__elseClosure'] = function() use ($renderingContext, $self) {
$output92 = '';

$output92 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments93 = array();
$arguments93['action'] = 'freeze';
$arguments93['class'] = 'neos-button neos-button-freeze';
// Rendering Array
$array94 = array();
$array94['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments93['arguments'] = $array94;
$arguments93['title'] = 'Click here to freeze the package in order to speed up your website.';
$arguments93['additionalAttributes'] = NULL;
$arguments93['data'] = NULL;
$arguments93['controller'] = NULL;
$arguments93['package'] = NULL;
$arguments93['subpackage'] = NULL;
$arguments93['section'] = '';
$arguments93['format'] = '';
$arguments93['additionalParams'] = array (
);
$arguments93['addQueryString'] = false;
$arguments93['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments93['useParentRequest'] = false;
$arguments93['absolute'] = true;
$arguments93['dir'] = NULL;
$arguments93['id'] = NULL;
$arguments93['lang'] = NULL;
$arguments93['style'] = NULL;
$arguments93['accesskey'] = NULL;
$arguments93['tabindex'] = NULL;
$arguments93['onclick'] = NULL;
$arguments93['name'] = NULL;
$arguments93['rel'] = NULL;
$arguments93['rev'] = NULL;
$arguments93['target'] = NULL;
$renderChildrenClosure95 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper96 = $self->getViewHelper('$viewHelper96', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper96->setArguments($arguments93);
$viewHelper96->setRenderingContext($renderingContext);
$viewHelper96->setRenderChildrenClosure($renderChildrenClosure95);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output92 .= $viewHelper96->initializeArgumentsAndRender();

$output92 .= '
													';
return $output92;
};
$viewHelper97 = $self->getViewHelper('$viewHelper97', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper97->setArguments($arguments68);
$viewHelper97->setRenderingContext($renderingContext);
$viewHelper97->setRenderChildrenClosure($renderChildrenClosure69);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output67 .= $viewHelper97->initializeArgumentsAndRender();

$output67 .= '
											';
return $output67;
};
$viewHelper98 = $self->getViewHelper('$viewHelper98', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper98->setArguments($arguments65);
$viewHelper98->setRenderingContext($renderingContext);
$viewHelper98->setRenderChildrenClosure($renderChildrenClosure66);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output64 .= $viewHelper98->initializeArgumentsAndRender();

$output64 .= '
											';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments99 = array();
// Rendering Boolean node
$arguments99['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isProtected', $renderingContext));
$arguments99['then'] = NULL;
$arguments99['else'] = NULL;
$renderChildrenClosure100 = function() use ($renderingContext, $self) {
$output101 = '';

$output101 .= '
												';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments102 = array();
$renderChildrenClosure103 = function() use ($renderingContext, $self) {
return '
													<button class="neos-button neos-button-warning neos-disabled" title="This package is protected and cannot be deactivated." disabled="disabled">
														<i class="icon-pause icon-white"></i>
													</button>
												';
};
$viewHelper104 = $self->getViewHelper('$viewHelper104', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper104->setArguments($arguments102);
$viewHelper104->setRenderingContext($renderingContext);
$viewHelper104->setRenderChildrenClosure($renderChildrenClosure103);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output101 .= $viewHelper104->initializeArgumentsAndRender();

$output101 .= '
												';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments105 = array();
$renderChildrenClosure106 = function() use ($renderingContext, $self) {
$output107 = '';

$output107 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments108 = array();
$arguments108['action'] = 'deactivate';
$arguments108['class'] = 'neos-button neos-button-warning';
// Rendering Array
$array109 = array();
$array109['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments108['arguments'] = $array109;
$output110 = '';

$output110 .= 'Click to deactivate ';

$output110 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);

$output110 .= '.';
$arguments108['title'] = $output110;
$arguments108['additionalAttributes'] = NULL;
$arguments108['data'] = NULL;
$arguments108['controller'] = NULL;
$arguments108['package'] = NULL;
$arguments108['subpackage'] = NULL;
$arguments108['section'] = '';
$arguments108['format'] = '';
$arguments108['additionalParams'] = array (
);
$arguments108['addQueryString'] = false;
$arguments108['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments108['useParentRequest'] = false;
$arguments108['absolute'] = true;
$arguments108['dir'] = NULL;
$arguments108['id'] = NULL;
$arguments108['lang'] = NULL;
$arguments108['style'] = NULL;
$arguments108['accesskey'] = NULL;
$arguments108['tabindex'] = NULL;
$arguments108['onclick'] = NULL;
$arguments108['name'] = NULL;
$arguments108['rel'] = NULL;
$arguments108['rev'] = NULL;
$arguments108['target'] = NULL;
$renderChildrenClosure111 = function() use ($renderingContext, $self) {
return '
														<i class="icon-pause icon-white"></i>
													';
};
$viewHelper112 = $self->getViewHelper('$viewHelper112', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper112->setArguments($arguments108);
$viewHelper112->setRenderingContext($renderingContext);
$viewHelper112->setRenderChildrenClosure($renderChildrenClosure111);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output107 .= $viewHelper112->initializeArgumentsAndRender();

$output107 .= '
												';
return $output107;
};
$viewHelper113 = $self->getViewHelper('$viewHelper113', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper113->setArguments($arguments105);
$viewHelper113->setRenderingContext($renderingContext);
$viewHelper113->setRenderChildrenClosure($renderChildrenClosure106);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output101 .= $viewHelper113->initializeArgumentsAndRender();

$output101 .= '
											';
return $output101;
};
$arguments99['__thenClosure'] = function() use ($renderingContext, $self) {
return '
													<button class="neos-button neos-button-warning neos-disabled" title="This package is protected and cannot be deactivated." disabled="disabled">
														<i class="icon-pause icon-white"></i>
													</button>
												';
};
$arguments99['__elseClosure'] = function() use ($renderingContext, $self) {
$output114 = '';

$output114 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments115 = array();
$arguments115['action'] = 'deactivate';
$arguments115['class'] = 'neos-button neos-button-warning';
// Rendering Array
$array116 = array();
$array116['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments115['arguments'] = $array116;
$output117 = '';

$output117 .= 'Click to deactivate ';

$output117 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);

$output117 .= '.';
$arguments115['title'] = $output117;
$arguments115['additionalAttributes'] = NULL;
$arguments115['data'] = NULL;
$arguments115['controller'] = NULL;
$arguments115['package'] = NULL;
$arguments115['subpackage'] = NULL;
$arguments115['section'] = '';
$arguments115['format'] = '';
$arguments115['additionalParams'] = array (
);
$arguments115['addQueryString'] = false;
$arguments115['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments115['useParentRequest'] = false;
$arguments115['absolute'] = true;
$arguments115['dir'] = NULL;
$arguments115['id'] = NULL;
$arguments115['lang'] = NULL;
$arguments115['style'] = NULL;
$arguments115['accesskey'] = NULL;
$arguments115['tabindex'] = NULL;
$arguments115['onclick'] = NULL;
$arguments115['name'] = NULL;
$arguments115['rel'] = NULL;
$arguments115['rev'] = NULL;
$arguments115['target'] = NULL;
$renderChildrenClosure118 = function() use ($renderingContext, $self) {
return '
														<i class="icon-pause icon-white"></i>
													';
};
$viewHelper119 = $self->getViewHelper('$viewHelper119', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper119->setArguments($arguments115);
$viewHelper119->setRenderingContext($renderingContext);
$viewHelper119->setRenderChildrenClosure($renderChildrenClosure118);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output114 .= $viewHelper119->initializeArgumentsAndRender();

$output114 .= '
												';
return $output114;
};
$viewHelper120 = $self->getViewHelper('$viewHelper120', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper120->setArguments($arguments99);
$viewHelper120->setRenderingContext($renderingContext);
$viewHelper120->setRenderChildrenClosure($renderChildrenClosure100);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output64 .= $viewHelper120->initializeArgumentsAndRender();

$output64 .= '
										';
return $output64;
};
$viewHelper121 = $self->getViewHelper('$viewHelper121', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper121->setArguments($arguments62);
$viewHelper121->setRenderingContext($renderingContext);
$viewHelper121->setRenderChildrenClosure($renderChildrenClosure63);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output61 .= $viewHelper121->initializeArgumentsAndRender();

$output61 .= '
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments122 = array();
$renderChildrenClosure123 = function() use ($renderingContext, $self) {
$output124 = '';

$output124 .= '
											';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments125 = array();
$arguments125['action'] = 'activate';
$arguments125['class'] = 'neos-button neos-button-success';
// Rendering Array
$array126 = array();
$array126['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments125['arguments'] = $array126;
$output127 = '';

$output127 .= 'Click to activate ';

$output127 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageName', $renderingContext);

$output127 .= '.';
$arguments125['title'] = $output127;
$arguments125['additionalAttributes'] = NULL;
$arguments125['data'] = NULL;
$arguments125['controller'] = NULL;
$arguments125['package'] = NULL;
$arguments125['subpackage'] = NULL;
$arguments125['section'] = '';
$arguments125['format'] = '';
$arguments125['additionalParams'] = array (
);
$arguments125['addQueryString'] = false;
$arguments125['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments125['useParentRequest'] = false;
$arguments125['absolute'] = true;
$arguments125['dir'] = NULL;
$arguments125['id'] = NULL;
$arguments125['lang'] = NULL;
$arguments125['style'] = NULL;
$arguments125['accesskey'] = NULL;
$arguments125['tabindex'] = NULL;
$arguments125['onclick'] = NULL;
$arguments125['name'] = NULL;
$arguments125['rel'] = NULL;
$arguments125['rev'] = NULL;
$arguments125['target'] = NULL;
$renderChildrenClosure128 = function() use ($renderingContext, $self) {
return '
												<i class="icon-play icon-white"></i>
											';
};
$viewHelper129 = $self->getViewHelper('$viewHelper129', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper129->setArguments($arguments125);
$viewHelper129->setRenderingContext($renderingContext);
$viewHelper129->setRenderChildrenClosure($renderChildrenClosure128);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output124 .= $viewHelper129->initializeArgumentsAndRender();

$output124 .= '
										';
return $output124;
};
$viewHelper130 = $self->getViewHelper('$viewHelper130', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper130->setArguments($arguments122);
$viewHelper130->setRenderingContext($renderingContext);
$viewHelper130->setRenderChildrenClosure($renderChildrenClosure123);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output61 .= $viewHelper130->initializeArgumentsAndRender();

$output61 .= '
									';
return $output61;
};
$arguments59['__thenClosure'] = function() use ($renderingContext, $self) {
$output131 = '';

$output131 .= '
											';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments132 = array();
// Rendering Boolean node
$arguments132['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'isDevelopmentContext', $renderingContext));
$arguments132['then'] = NULL;
$arguments132['else'] = NULL;
$renderChildrenClosure133 = function() use ($renderingContext, $self) {
$output134 = '';

$output134 .= '
												';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments135 = array();
// Rendering Boolean node
$arguments135['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isFrozen', $renderingContext));
$arguments135['then'] = NULL;
$arguments135['else'] = NULL;
$renderChildrenClosure136 = function() use ($renderingContext, $self) {
$output137 = '';

$output137 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments138 = array();
$renderChildrenClosure139 = function() use ($renderingContext, $self) {
$output140 = '';

$output140 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments141 = array();
$arguments141['action'] = 'unfreeze';
$arguments141['class'] = 'neos-button neos-button-freeze neos-active';
// Rendering Array
$array142 = array();
$array142['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments141['arguments'] = $array142;
$arguments141['title'] = 'This package is currently frozen. Click here to unfreeze it.';
$arguments141['additionalAttributes'] = NULL;
$arguments141['data'] = NULL;
$arguments141['controller'] = NULL;
$arguments141['package'] = NULL;
$arguments141['subpackage'] = NULL;
$arguments141['section'] = '';
$arguments141['format'] = '';
$arguments141['additionalParams'] = array (
);
$arguments141['addQueryString'] = false;
$arguments141['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments141['useParentRequest'] = false;
$arguments141['absolute'] = true;
$arguments141['dir'] = NULL;
$arguments141['id'] = NULL;
$arguments141['lang'] = NULL;
$arguments141['style'] = NULL;
$arguments141['accesskey'] = NULL;
$arguments141['tabindex'] = NULL;
$arguments141['onclick'] = NULL;
$arguments141['name'] = NULL;
$arguments141['rel'] = NULL;
$arguments141['rev'] = NULL;
$arguments141['target'] = NULL;
$renderChildrenClosure143 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper144 = $self->getViewHelper('$viewHelper144', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper144->setArguments($arguments141);
$viewHelper144->setRenderingContext($renderingContext);
$viewHelper144->setRenderChildrenClosure($renderChildrenClosure143);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output140 .= $viewHelper144->initializeArgumentsAndRender();

$output140 .= '
													';
return $output140;
};
$viewHelper145 = $self->getViewHelper('$viewHelper145', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper145->setArguments($arguments138);
$viewHelper145->setRenderingContext($renderingContext);
$viewHelper145->setRenderChildrenClosure($renderChildrenClosure139);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output137 .= $viewHelper145->initializeArgumentsAndRender();

$output137 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments146 = array();
$renderChildrenClosure147 = function() use ($renderingContext, $self) {
$output148 = '';

$output148 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments149 = array();
$arguments149['action'] = 'freeze';
$arguments149['class'] = 'neos-button neos-button-freeze';
// Rendering Array
$array150 = array();
$array150['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments149['arguments'] = $array150;
$arguments149['title'] = 'Click here to freeze the package in order to speed up your website.';
$arguments149['additionalAttributes'] = NULL;
$arguments149['data'] = NULL;
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
$renderChildrenClosure151 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper152 = $self->getViewHelper('$viewHelper152', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper152->setArguments($arguments149);
$viewHelper152->setRenderingContext($renderingContext);
$viewHelper152->setRenderChildrenClosure($renderChildrenClosure151);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output148 .= $viewHelper152->initializeArgumentsAndRender();

$output148 .= '
													';
return $output148;
};
$viewHelper153 = $self->getViewHelper('$viewHelper153', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper153->setArguments($arguments146);
$viewHelper153->setRenderingContext($renderingContext);
$viewHelper153->setRenderChildrenClosure($renderChildrenClosure147);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output137 .= $viewHelper153->initializeArgumentsAndRender();

$output137 .= '
												';
return $output137;
};
$arguments135['__thenClosure'] = function() use ($renderingContext, $self) {
$output154 = '';

$output154 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments155 = array();
$arguments155['action'] = 'unfreeze';
$arguments155['class'] = 'neos-button neos-button-freeze neos-active';
// Rendering Array
$array156 = array();
$array156['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments155['arguments'] = $array156;
$arguments155['title'] = 'This package is currently frozen. Click here to unfreeze it.';
$arguments155['additionalAttributes'] = NULL;
$arguments155['data'] = NULL;
$arguments155['controller'] = NULL;
$arguments155['package'] = NULL;
$arguments155['subpackage'] = NULL;
$arguments155['section'] = '';
$arguments155['format'] = '';
$arguments155['additionalParams'] = array (
);
$arguments155['addQueryString'] = false;
$arguments155['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments155['useParentRequest'] = false;
$arguments155['absolute'] = true;
$arguments155['dir'] = NULL;
$arguments155['id'] = NULL;
$arguments155['lang'] = NULL;
$arguments155['style'] = NULL;
$arguments155['accesskey'] = NULL;
$arguments155['tabindex'] = NULL;
$arguments155['onclick'] = NULL;
$arguments155['name'] = NULL;
$arguments155['rel'] = NULL;
$arguments155['rev'] = NULL;
$arguments155['target'] = NULL;
$renderChildrenClosure157 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper158 = $self->getViewHelper('$viewHelper158', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper158->setArguments($arguments155);
$viewHelper158->setRenderingContext($renderingContext);
$viewHelper158->setRenderChildrenClosure($renderChildrenClosure157);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output154 .= $viewHelper158->initializeArgumentsAndRender();

$output154 .= '
													';
return $output154;
};
$arguments135['__elseClosure'] = function() use ($renderingContext, $self) {
$output159 = '';

$output159 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments160 = array();
$arguments160['action'] = 'freeze';
$arguments160['class'] = 'neos-button neos-button-freeze';
// Rendering Array
$array161 = array();
$array161['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments160['arguments'] = $array161;
$arguments160['title'] = 'Click here to freeze the package in order to speed up your website.';
$arguments160['additionalAttributes'] = NULL;
$arguments160['data'] = NULL;
$arguments160['controller'] = NULL;
$arguments160['package'] = NULL;
$arguments160['subpackage'] = NULL;
$arguments160['section'] = '';
$arguments160['format'] = '';
$arguments160['additionalParams'] = array (
);
$arguments160['addQueryString'] = false;
$arguments160['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments160['useParentRequest'] = false;
$arguments160['absolute'] = true;
$arguments160['dir'] = NULL;
$arguments160['id'] = NULL;
$arguments160['lang'] = NULL;
$arguments160['style'] = NULL;
$arguments160['accesskey'] = NULL;
$arguments160['tabindex'] = NULL;
$arguments160['onclick'] = NULL;
$arguments160['name'] = NULL;
$arguments160['rel'] = NULL;
$arguments160['rev'] = NULL;
$arguments160['target'] = NULL;
$renderChildrenClosure162 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper163 = $self->getViewHelper('$viewHelper163', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper163->setArguments($arguments160);
$viewHelper163->setRenderingContext($renderingContext);
$viewHelper163->setRenderChildrenClosure($renderChildrenClosure162);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output159 .= $viewHelper163->initializeArgumentsAndRender();

$output159 .= '
													';
return $output159;
};
$viewHelper164 = $self->getViewHelper('$viewHelper164', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper164->setArguments($arguments135);
$viewHelper164->setRenderingContext($renderingContext);
$viewHelper164->setRenderChildrenClosure($renderChildrenClosure136);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output134 .= $viewHelper164->initializeArgumentsAndRender();

$output134 .= '
											';
return $output134;
};
$viewHelper165 = $self->getViewHelper('$viewHelper165', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper165->setArguments($arguments132);
$viewHelper165->setRenderingContext($renderingContext);
$viewHelper165->setRenderChildrenClosure($renderChildrenClosure133);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output131 .= $viewHelper165->initializeArgumentsAndRender();

$output131 .= '
											';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments166 = array();
// Rendering Boolean node
$arguments166['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isProtected', $renderingContext));
$arguments166['then'] = NULL;
$arguments166['else'] = NULL;
$renderChildrenClosure167 = function() use ($renderingContext, $self) {
$output168 = '';

$output168 .= '
												';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments169 = array();
$renderChildrenClosure170 = function() use ($renderingContext, $self) {
return '
													<button class="neos-button neos-button-warning neos-disabled" title="This package is protected and cannot be deactivated." disabled="disabled">
														<i class="icon-pause icon-white"></i>
													</button>
												';
};
$viewHelper171 = $self->getViewHelper('$viewHelper171', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper171->setArguments($arguments169);
$viewHelper171->setRenderingContext($renderingContext);
$viewHelper171->setRenderChildrenClosure($renderChildrenClosure170);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output168 .= $viewHelper171->initializeArgumentsAndRender();

$output168 .= '
												';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments172 = array();
$renderChildrenClosure173 = function() use ($renderingContext, $self) {
$output174 = '';

$output174 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments175 = array();
$arguments175['action'] = 'deactivate';
$arguments175['class'] = 'neos-button neos-button-warning';
// Rendering Array
$array176 = array();
$array176['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments175['arguments'] = $array176;
$output177 = '';

$output177 .= 'Click to deactivate ';

$output177 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);

$output177 .= '.';
$arguments175['title'] = $output177;
$arguments175['additionalAttributes'] = NULL;
$arguments175['data'] = NULL;
$arguments175['controller'] = NULL;
$arguments175['package'] = NULL;
$arguments175['subpackage'] = NULL;
$arguments175['section'] = '';
$arguments175['format'] = '';
$arguments175['additionalParams'] = array (
);
$arguments175['addQueryString'] = false;
$arguments175['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments175['useParentRequest'] = false;
$arguments175['absolute'] = true;
$arguments175['dir'] = NULL;
$arguments175['id'] = NULL;
$arguments175['lang'] = NULL;
$arguments175['style'] = NULL;
$arguments175['accesskey'] = NULL;
$arguments175['tabindex'] = NULL;
$arguments175['onclick'] = NULL;
$arguments175['name'] = NULL;
$arguments175['rel'] = NULL;
$arguments175['rev'] = NULL;
$arguments175['target'] = NULL;
$renderChildrenClosure178 = function() use ($renderingContext, $self) {
return '
														<i class="icon-pause icon-white"></i>
													';
};
$viewHelper179 = $self->getViewHelper('$viewHelper179', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper179->setArguments($arguments175);
$viewHelper179->setRenderingContext($renderingContext);
$viewHelper179->setRenderChildrenClosure($renderChildrenClosure178);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output174 .= $viewHelper179->initializeArgumentsAndRender();

$output174 .= '
												';
return $output174;
};
$viewHelper180 = $self->getViewHelper('$viewHelper180', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper180->setArguments($arguments172);
$viewHelper180->setRenderingContext($renderingContext);
$viewHelper180->setRenderChildrenClosure($renderChildrenClosure173);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output168 .= $viewHelper180->initializeArgumentsAndRender();

$output168 .= '
											';
return $output168;
};
$arguments166['__thenClosure'] = function() use ($renderingContext, $self) {
return '
													<button class="neos-button neos-button-warning neos-disabled" title="This package is protected and cannot be deactivated." disabled="disabled">
														<i class="icon-pause icon-white"></i>
													</button>
												';
};
$arguments166['__elseClosure'] = function() use ($renderingContext, $self) {
$output181 = '';

$output181 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments182 = array();
$arguments182['action'] = 'deactivate';
$arguments182['class'] = 'neos-button neos-button-warning';
// Rendering Array
$array183 = array();
$array183['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments182['arguments'] = $array183;
$output184 = '';

$output184 .= 'Click to deactivate ';

$output184 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);

$output184 .= '.';
$arguments182['title'] = $output184;
$arguments182['additionalAttributes'] = NULL;
$arguments182['data'] = NULL;
$arguments182['controller'] = NULL;
$arguments182['package'] = NULL;
$arguments182['subpackage'] = NULL;
$arguments182['section'] = '';
$arguments182['format'] = '';
$arguments182['additionalParams'] = array (
);
$arguments182['addQueryString'] = false;
$arguments182['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments182['useParentRequest'] = false;
$arguments182['absolute'] = true;
$arguments182['dir'] = NULL;
$arguments182['id'] = NULL;
$arguments182['lang'] = NULL;
$arguments182['style'] = NULL;
$arguments182['accesskey'] = NULL;
$arguments182['tabindex'] = NULL;
$arguments182['onclick'] = NULL;
$arguments182['name'] = NULL;
$arguments182['rel'] = NULL;
$arguments182['rev'] = NULL;
$arguments182['target'] = NULL;
$renderChildrenClosure185 = function() use ($renderingContext, $self) {
return '
														<i class="icon-pause icon-white"></i>
													';
};
$viewHelper186 = $self->getViewHelper('$viewHelper186', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper186->setArguments($arguments182);
$viewHelper186->setRenderingContext($renderingContext);
$viewHelper186->setRenderChildrenClosure($renderChildrenClosure185);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output181 .= $viewHelper186->initializeArgumentsAndRender();

$output181 .= '
												';
return $output181;
};
$viewHelper187 = $self->getViewHelper('$viewHelper187', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper187->setArguments($arguments166);
$viewHelper187->setRenderingContext($renderingContext);
$viewHelper187->setRenderChildrenClosure($renderChildrenClosure167);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output131 .= $viewHelper187->initializeArgumentsAndRender();

$output131 .= '
										';
return $output131;
};
$arguments59['__elseClosure'] = function() use ($renderingContext, $self) {
$output188 = '';

$output188 .= '
											';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments189 = array();
$arguments189['action'] = 'activate';
$arguments189['class'] = 'neos-button neos-button-success';
// Rendering Array
$array190 = array();
$array190['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments189['arguments'] = $array190;
$output191 = '';

$output191 .= 'Click to activate ';

$output191 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageName', $renderingContext);

$output191 .= '.';
$arguments189['title'] = $output191;
$arguments189['additionalAttributes'] = NULL;
$arguments189['data'] = NULL;
$arguments189['controller'] = NULL;
$arguments189['package'] = NULL;
$arguments189['subpackage'] = NULL;
$arguments189['section'] = '';
$arguments189['format'] = '';
$arguments189['additionalParams'] = array (
);
$arguments189['addQueryString'] = false;
$arguments189['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments189['useParentRequest'] = false;
$arguments189['absolute'] = true;
$arguments189['dir'] = NULL;
$arguments189['id'] = NULL;
$arguments189['lang'] = NULL;
$arguments189['style'] = NULL;
$arguments189['accesskey'] = NULL;
$arguments189['tabindex'] = NULL;
$arguments189['onclick'] = NULL;
$arguments189['name'] = NULL;
$arguments189['rel'] = NULL;
$arguments189['rev'] = NULL;
$arguments189['target'] = NULL;
$renderChildrenClosure192 = function() use ($renderingContext, $self) {
return '
												<i class="icon-play icon-white"></i>
											';
};
$viewHelper193 = $self->getViewHelper('$viewHelper193', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper193->setArguments($arguments189);
$viewHelper193->setRenderingContext($renderingContext);
$viewHelper193->setRenderChildrenClosure($renderChildrenClosure192);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output188 .= $viewHelper193->initializeArgumentsAndRender();

$output188 .= '
										';
return $output188;
};
$viewHelper194 = $self->getViewHelper('$viewHelper194', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper194->setArguments($arguments59);
$viewHelper194->setRenderingContext($renderingContext);
$viewHelper194->setRenderChildrenClosure($renderChildrenClosure60);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output15 .= $viewHelper194->initializeArgumentsAndRender();

$output15 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments195 = array();
// Rendering Boolean node
$arguments195['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isProtected', $renderingContext));
$arguments195['then'] = NULL;
$arguments195['else'] = NULL;
$renderChildrenClosure196 = function() use ($renderingContext, $self) {
$output197 = '';

$output197 .= '
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments198 = array();
$renderChildrenClosure199 = function() use ($renderingContext, $self) {
return '
											<button class="neos-button neos-button-danger neos-disabled" title="This package is protected and cannot be deleted." disabled="disabled"><i class="icon-trash icon-white"></i></button>
										';
};
$viewHelper200 = $self->getViewHelper('$viewHelper200', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper200->setArguments($arguments198);
$viewHelper200->setRenderingContext($renderingContext);
$viewHelper200->setRenderChildrenClosure($renderChildrenClosure199);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output197 .= $viewHelper200->initializeArgumentsAndRender();

$output197 .= '
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments201 = array();
$renderChildrenClosure202 = function() use ($renderingContext, $self) {
$output203 = '';

$output203 .= '
											<button class="neos-button neos-button-danger" title="Click to delete ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments204 = array();
$arguments204['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments204['keepQuotes'] = false;
$arguments204['encoding'] = 'UTF-8';
$arguments204['doubleEncode'] = true;
$renderChildrenClosure205 = function() use ($renderingContext, $self) {
return NULL;
};
$value206 = ($arguments204['value'] !== NULL ? $arguments204['value'] : $renderChildrenClosure205());

$output203 .= !is_string($value206) && !(is_object($value206) && method_exists($value206, '__toString')) ? $value206 : htmlspecialchars($value206, ($arguments204['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments204['encoding'], $arguments204['doubleEncode']);

$output203 .= '." data-toggle="modal" href="#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments207 = array();
$arguments207['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.sanitizedPackageKey', $renderingContext);
$arguments207['keepQuotes'] = false;
$arguments207['encoding'] = 'UTF-8';
$arguments207['doubleEncode'] = true;
$renderChildrenClosure208 = function() use ($renderingContext, $self) {
return NULL;
};
$value209 = ($arguments207['value'] !== NULL ? $arguments207['value'] : $renderChildrenClosure208());

$output203 .= !is_string($value209) && !(is_object($value209) && method_exists($value209, '__toString')) ? $value209 : htmlspecialchars($value209, ($arguments207['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments207['encoding'], $arguments207['doubleEncode']);

$output203 .= '"><i class="icon-trash icon-white"></i></button>
											<div class="neos-hide" id="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments210 = array();
$arguments210['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.sanitizedPackageKey', $renderingContext);
$arguments210['keepQuotes'] = false;
$arguments210['encoding'] = 'UTF-8';
$arguments210['doubleEncode'] = true;
$renderChildrenClosure211 = function() use ($renderingContext, $self) {
return NULL;
};
$value212 = ($arguments210['value'] !== NULL ? $arguments210['value'] : $renderChildrenClosure211());

$output203 .= !is_string($value212) && !(is_object($value212) && method_exists($value212, '__toString')) ? $value212 : htmlspecialchars($value212, ($arguments210['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments210['encoding'], $arguments210['doubleEncode']);

$output203 .= '">
												<div class="neos-modal">
													<div class="neos-modal-header">
														<button type="button" class="neos-close neos-button" data-dismiss="modal"></button>
														<div class="neos-header">Do you really want to delete "';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments213 = array();
$arguments213['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments213['keepQuotes'] = false;
$arguments213['encoding'] = 'UTF-8';
$arguments213['doubleEncode'] = true;
$renderChildrenClosure214 = function() use ($renderingContext, $self) {
return NULL;
};
$value215 = ($arguments213['value'] !== NULL ? $arguments213['value'] : $renderChildrenClosure214());

$output203 .= !is_string($value215) && !(is_object($value215) && method_exists($value215, '__toString')) ? $value215 : htmlspecialchars($value215, ($arguments213['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments213['encoding'], $arguments213['doubleEncode']);

$output203 .= '"?</div>
													</div>
													<div class="neos-modal-footer">
														<a href="#" class="neos-button" data-dismiss="modal">Cancel</a>
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments216 = array();
$arguments216['action'] = 'delete';
$arguments216['class'] = 'neos-button neos-button-danger';
// Rendering Array
$array217 = array();
$array217['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments216['arguments'] = $array217;
$arguments216['title'] = 'Delete';
$arguments216['additionalAttributes'] = NULL;
$arguments216['data'] = NULL;
$arguments216['controller'] = NULL;
$arguments216['package'] = NULL;
$arguments216['subpackage'] = NULL;
$arguments216['section'] = '';
$arguments216['format'] = '';
$arguments216['additionalParams'] = array (
);
$arguments216['addQueryString'] = false;
$arguments216['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments216['useParentRequest'] = false;
$arguments216['absolute'] = true;
$arguments216['dir'] = NULL;
$arguments216['id'] = NULL;
$arguments216['lang'] = NULL;
$arguments216['style'] = NULL;
$arguments216['accesskey'] = NULL;
$arguments216['tabindex'] = NULL;
$arguments216['onclick'] = NULL;
$arguments216['name'] = NULL;
$arguments216['rel'] = NULL;
$arguments216['rev'] = NULL;
$arguments216['target'] = NULL;
$renderChildrenClosure218 = function() use ($renderingContext, $self) {
return 'Yes, delete the package';
};
$viewHelper219 = $self->getViewHelper('$viewHelper219', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper219->setArguments($arguments216);
$viewHelper219->setRenderingContext($renderingContext);
$viewHelper219->setRenderChildrenClosure($renderChildrenClosure218);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output203 .= $viewHelper219->initializeArgumentsAndRender();

$output203 .= '
													</div>
												</div>
												<div class="neos-modal-backdrop neos-in"></div>
											</div>
										';
return $output203;
};
$viewHelper220 = $self->getViewHelper('$viewHelper220', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper220->setArguments($arguments201);
$viewHelper220->setRenderingContext($renderingContext);
$viewHelper220->setRenderChildrenClosure($renderChildrenClosure202);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output197 .= $viewHelper220->initializeArgumentsAndRender();

$output197 .= '
									';
return $output197;
};
$arguments195['__thenClosure'] = function() use ($renderingContext, $self) {
return '
											<button class="neos-button neos-button-danger neos-disabled" title="This package is protected and cannot be deleted." disabled="disabled"><i class="icon-trash icon-white"></i></button>
										';
};
$arguments195['__elseClosure'] = function() use ($renderingContext, $self) {
$output221 = '';

$output221 .= '
											<button class="neos-button neos-button-danger" title="Click to delete ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments222 = array();
$arguments222['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments222['keepQuotes'] = false;
$arguments222['encoding'] = 'UTF-8';
$arguments222['doubleEncode'] = true;
$renderChildrenClosure223 = function() use ($renderingContext, $self) {
return NULL;
};
$value224 = ($arguments222['value'] !== NULL ? $arguments222['value'] : $renderChildrenClosure223());

$output221 .= !is_string($value224) && !(is_object($value224) && method_exists($value224, '__toString')) ? $value224 : htmlspecialchars($value224, ($arguments222['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments222['encoding'], $arguments222['doubleEncode']);

$output221 .= '." data-toggle="modal" href="#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments225 = array();
$arguments225['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.sanitizedPackageKey', $renderingContext);
$arguments225['keepQuotes'] = false;
$arguments225['encoding'] = 'UTF-8';
$arguments225['doubleEncode'] = true;
$renderChildrenClosure226 = function() use ($renderingContext, $self) {
return NULL;
};
$value227 = ($arguments225['value'] !== NULL ? $arguments225['value'] : $renderChildrenClosure226());

$output221 .= !is_string($value227) && !(is_object($value227) && method_exists($value227, '__toString')) ? $value227 : htmlspecialchars($value227, ($arguments225['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments225['encoding'], $arguments225['doubleEncode']);

$output221 .= '"><i class="icon-trash icon-white"></i></button>
											<div class="neos-hide" id="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments228 = array();
$arguments228['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.sanitizedPackageKey', $renderingContext);
$arguments228['keepQuotes'] = false;
$arguments228['encoding'] = 'UTF-8';
$arguments228['doubleEncode'] = true;
$renderChildrenClosure229 = function() use ($renderingContext, $self) {
return NULL;
};
$value230 = ($arguments228['value'] !== NULL ? $arguments228['value'] : $renderChildrenClosure229());

$output221 .= !is_string($value230) && !(is_object($value230) && method_exists($value230, '__toString')) ? $value230 : htmlspecialchars($value230, ($arguments228['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments228['encoding'], $arguments228['doubleEncode']);

$output221 .= '">
												<div class="neos-modal">
													<div class="neos-modal-header">
														<button type="button" class="neos-close neos-button" data-dismiss="modal"></button>
														<div class="neos-header">Do you really want to delete "';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments231 = array();
$arguments231['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments231['keepQuotes'] = false;
$arguments231['encoding'] = 'UTF-8';
$arguments231['doubleEncode'] = true;
$renderChildrenClosure232 = function() use ($renderingContext, $self) {
return NULL;
};
$value233 = ($arguments231['value'] !== NULL ? $arguments231['value'] : $renderChildrenClosure232());

$output221 .= !is_string($value233) && !(is_object($value233) && method_exists($value233, '__toString')) ? $value233 : htmlspecialchars($value233, ($arguments231['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments231['encoding'], $arguments231['doubleEncode']);

$output221 .= '"?</div>
													</div>
													<div class="neos-modal-footer">
														<a href="#" class="neos-button" data-dismiss="modal">Cancel</a>
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments234 = array();
$arguments234['action'] = 'delete';
$arguments234['class'] = 'neos-button neos-button-danger';
// Rendering Array
$array235 = array();
$array235['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments234['arguments'] = $array235;
$arguments234['title'] = 'Delete';
$arguments234['additionalAttributes'] = NULL;
$arguments234['data'] = NULL;
$arguments234['controller'] = NULL;
$arguments234['package'] = NULL;
$arguments234['subpackage'] = NULL;
$arguments234['section'] = '';
$arguments234['format'] = '';
$arguments234['additionalParams'] = array (
);
$arguments234['addQueryString'] = false;
$arguments234['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments234['useParentRequest'] = false;
$arguments234['absolute'] = true;
$arguments234['dir'] = NULL;
$arguments234['id'] = NULL;
$arguments234['lang'] = NULL;
$arguments234['style'] = NULL;
$arguments234['accesskey'] = NULL;
$arguments234['tabindex'] = NULL;
$arguments234['onclick'] = NULL;
$arguments234['name'] = NULL;
$arguments234['rel'] = NULL;
$arguments234['rev'] = NULL;
$arguments234['target'] = NULL;
$renderChildrenClosure236 = function() use ($renderingContext, $self) {
return 'Yes, delete the package';
};
$viewHelper237 = $self->getViewHelper('$viewHelper237', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper237->setArguments($arguments234);
$viewHelper237->setRenderingContext($renderingContext);
$viewHelper237->setRenderChildrenClosure($renderChildrenClosure236);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output221 .= $viewHelper237->initializeArgumentsAndRender();

$output221 .= '
													</div>
												</div>
												<div class="neos-modal-backdrop neos-in"></div>
											</div>
										';
return $output221;
};
$viewHelper238 = $self->getViewHelper('$viewHelper238', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper238->setArguments($arguments195);
$viewHelper238->setRenderingContext($renderingContext);
$viewHelper238->setRenderChildrenClosure($renderChildrenClosure196);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output15 .= $viewHelper238->initializeArgumentsAndRender();

$output15 .= '
								</td>
							</tr>
						';
return $output15;
};

$output6 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments13, $renderChildrenClosure14, $renderingContext);

$output6 .= '
					';
return $output6;
};

$output3 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments4, $renderChildrenClosure5, $renderingContext);

$output3 .= '
				</tbody>
			</table>
		</div>
		<div class="neos-footer">
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments239 = array();
// Rendering Boolean node
$arguments239['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'isDevelopmentContext', $renderingContext));
$arguments239['then'] = NULL;
$arguments239['else'] = NULL;
$renderChildrenClosure240 = function() use ($renderingContext, $self) {
return '
				<button type="submit" name="moduleArguments[action]" value="freeze" class="neos-button batch-action neos-disabled" disabled="disabled">
					Freeze <strong>selected</strong> packages
				</button>
				<button type="submit" name="moduleArguments[action]" value="unfreeze" class="neos-button batch-action neos-disabled" disabled="disabled">
					Unfreeze <strong>selected</strong> packages
				</button>
			';
};
$viewHelper241 = $self->getViewHelper('$viewHelper241', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper241->setArguments($arguments239);
$viewHelper241->setRenderingContext($renderingContext);
$viewHelper241->setRenderChildrenClosure($renderChildrenClosure240);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output3 .= $viewHelper241->initializeArgumentsAndRender();

$output3 .= '
			<button class="neos-button neos-button-danger batch-action neos-disabled" data-toggle="modal" href="#delete" disabled="disabled">
				Delete <strong>selected</strong> packages
			</button>
			<button type="submit" name="moduleArguments[action]" value="deactivate" class="neos-button neos-button-warning batch-action neos-disabled" disabled="disabled">
				Deactivate <strong>selected</strong> packages
			</button>
			<button type="submit" name="moduleArguments[action]" value="activate" class="neos-button neos-button-success batch-action neos-disabled" disabled="disabled">
				Activate <strong>selected</strong> packages
			</button>
		</div>
		<div class="neos-hide" id="delete">
			<div class="neos-modal">
				<div class="neos-modal-header">
					<button type="button" class="neos-close" data-dismiss="modal"></button>
					<div class="neos-header">Do you really want to delete the selected packages? This action cannot be undone.</div>
				</div>
				<div class="neos-modal-footer">
					<a href="#" class="neos-button" data-dismiss="modal">Cancel</a>
					<button type="submit" name="moduleArguments[action]" value="delete" class="neos-button neos-button-danger">
						Yes, delete them
					</button>
				</div>
			</div>
			<div class="neos-modal-backdrop neos-in"></div>
		</div>
	';
return $output3;
};
$viewHelper242 = $self->getViewHelper('$viewHelper242', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper242->setArguments($arguments1);
$viewHelper242->setRenderingContext($renderingContext);
$viewHelper242->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output0 .= $viewHelper242->initializeArgumentsAndRender();

$output0 .= '

	<script>
		(function($) {
			$(\'#check-all\').change(function() {
				var value = false;
				if ($(this).is(\':checked\')) {
					value = true;
					$(\'.batch-action\').removeClass(\'neos-disabled\').removeAttr(\'disabled\');
				} else {
					$(\'.batch-action\').addClass(\'neos-disabled\').attr(\'disabled\', \'disabled\');
				}
				$(\'tbody input[type="checkbox"]\').prop(\'checked\', value);
			});
			$(\'tbody input[type="checkbox"]\').change(function() {
				if ($(\'tbody input[type="checkbox"]:checked\').length > 0) {
					$(\'.batch-action\').removeClass(\'neos-disabled\').removeAttr(\'disabled\')
				} else {
					$(\'.batch-action\').addClass(\'neos-disabled\').attr(\'disabled\', \'disabled\');
				}
			});
			$(\'.fold-toggle\').click(function() {
				$(this).toggleClass(\'icon-chevron-down icon-chevron-up\');
				$(\'tr.\' + $(this).data(\'toggle\')).toggle();
			});
		})(jQuery);
	</script>
';

return $output0;
}
/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output243 = '';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper
$arguments244 = array();
$arguments244['name'] = 'BackendSubModule';
$renderChildrenClosure245 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper246 = $self->getViewHelper('$viewHelper246', $renderingContext, 'TYPO3\Fluid\ViewHelpers\LayoutViewHelper');
$viewHelper246->setArguments($arguments244);
$viewHelper246->setRenderingContext($renderingContext);
$viewHelper246->setRenderChildrenClosure($renderChildrenClosure245);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\LayoutViewHelper

$output243 .= $viewHelper246->initializeArgumentsAndRender();

$output243 .= '

';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\SectionViewHelper
$arguments247 = array();
$arguments247['name'] = 'content';
$renderChildrenClosure248 = function() use ($renderingContext, $self) {
$output249 = '';

$output249 .= '
	';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper
$arguments250 = array();
$arguments250['action'] = 'batch';
$arguments250['additionalAttributes'] = NULL;
$arguments250['data'] = NULL;
$arguments250['arguments'] = array (
);
$arguments250['controller'] = NULL;
$arguments250['package'] = NULL;
$arguments250['subpackage'] = NULL;
$arguments250['object'] = NULL;
$arguments250['section'] = '';
$arguments250['format'] = '';
$arguments250['additionalParams'] = array (
);
$arguments250['absolute'] = false;
$arguments250['addQueryString'] = false;
$arguments250['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments250['fieldNamePrefix'] = NULL;
$arguments250['actionUri'] = NULL;
$arguments250['objectName'] = NULL;
$arguments250['useParentRequest'] = false;
$arguments250['enctype'] = NULL;
$arguments250['method'] = NULL;
$arguments250['name'] = NULL;
$arguments250['onreset'] = NULL;
$arguments250['onsubmit'] = NULL;
$arguments250['class'] = NULL;
$arguments250['dir'] = NULL;
$arguments250['id'] = NULL;
$arguments250['lang'] = NULL;
$arguments250['style'] = NULL;
$arguments250['title'] = NULL;
$arguments250['accesskey'] = NULL;
$arguments250['tabindex'] = NULL;
$arguments250['onclick'] = NULL;
$renderChildrenClosure251 = function() use ($renderingContext, $self) {
$output252 = '';

$output252 .= '
		<div class="neos-row-fluid">
			<legend>Available packages</legend>
			<br />
			<table class="neos-table">
				<thead>
					<th class="check">
						<label for="check-all" class="neos-checkbox">
							<input type="checkbox" id="check-all" /><span></span>
						</label>
					</th>
					<th>Package Name</th>
					<th>Version</th>
					<th>Package Key</th>
					<th>Package Type</th>
					<th>&nbsp;</th>
				</thead>
				<tbody>
					';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments253 = array();
$arguments253['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageGroups', $renderingContext);
$arguments253['key'] = 'packageGroup';
$arguments253['as'] = 'packages';
$arguments253['reverse'] = false;
$arguments253['iteration'] = NULL;
$renderChildrenClosure254 = function() use ($renderingContext, $self) {
$output255 = '';

$output255 .= '
						<tr class="neos-folder">
							<td colspan="2" class="neos-priority1">
								<strong>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments256 = array();
$arguments256['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageGroup', $renderingContext);
$arguments256['keepQuotes'] = false;
$arguments256['encoding'] = 'UTF-8';
$arguments256['doubleEncode'] = true;
$renderChildrenClosure257 = function() use ($renderingContext, $self) {
return NULL;
};
$value258 = ($arguments256['value'] !== NULL ? $arguments256['value'] : $renderChildrenClosure257());

$output255 .= !is_string($value258) && !(is_object($value258) && method_exists($value258, '__toString')) ? $value258 : htmlspecialchars($value258, ($arguments256['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments256['encoding'], $arguments256['doubleEncode']);

$output255 .= '</strong>
							</td>
							<td class="neos-priority2">&nbsp;</td>
							<td class="neos-priority3">&nbsp;</td>
							<td class="neos-priority3">&nbsp;</td>
							<td class="neos-priority1 neos-aRight">
								<i class="fold-toggle icon-chevron-up icon-white" data-toggle="fold-';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments259 = array();
$arguments259['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageGroup', $renderingContext);
$arguments259['keepQuotes'] = false;
$arguments259['encoding'] = 'UTF-8';
$arguments259['doubleEncode'] = true;
$renderChildrenClosure260 = function() use ($renderingContext, $self) {
return NULL;
};
$value261 = ($arguments259['value'] !== NULL ? $arguments259['value'] : $renderChildrenClosure260());

$output255 .= !is_string($value261) && !(is_object($value261) && method_exists($value261, '__toString')) ? $value261 : htmlspecialchars($value261, ($arguments259['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments259['encoding'], $arguments259['doubleEncode']);

$output255 .= '"></i>
							</td>
						</tr>
						';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments262 = array();
$arguments262['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packages', $renderingContext);
$arguments262['key'] = 'packageKey';
$arguments262['as'] = 'package';
$arguments262['reverse'] = false;
$arguments262['iteration'] = NULL;
$renderChildrenClosure263 = function() use ($renderingContext, $self) {
$output264 = '';

$output264 .= '
							<tr class="fold-';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments265 = array();
$arguments265['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageGroup', $renderingContext);
$arguments265['keepQuotes'] = false;
$arguments265['encoding'] = 'UTF-8';
$arguments265['doubleEncode'] = true;
$renderChildrenClosure266 = function() use ($renderingContext, $self) {
return NULL;
};
$value267 = ($arguments265['value'] !== NULL ? $arguments265['value'] : $renderChildrenClosure266());

$output264 .= !is_string($value267) && !(is_object($value267) && method_exists($value267, '__toString')) ? $value267 : htmlspecialchars($value267, ($arguments265['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments265['encoding'], $arguments265['doubleEncode']);
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments268 = array();
// Rendering Boolean node
$arguments268['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isActive', $renderingContext));
$arguments268['else'] = ' muted';
$arguments268['then'] = NULL;
$renderChildrenClosure269 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper270 = $self->getViewHelper('$viewHelper270', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper270->setArguments($arguments268);
$viewHelper270->setRenderingContext($renderingContext);
$viewHelper270->setRenderChildrenClosure($renderChildrenClosure269);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output264 .= $viewHelper270->initializeArgumentsAndRender();

$output264 .= '"';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments271 = array();
// Rendering Boolean node
$arguments271['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.description', $renderingContext));
$output272 = '';

$output272 .= ' title="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments273 = array();
$arguments273['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.description', $renderingContext);
$arguments273['keepQuotes'] = false;
$arguments273['encoding'] = 'UTF-8';
$arguments273['doubleEncode'] = true;
$renderChildrenClosure274 = function() use ($renderingContext, $self) {
return NULL;
};
$value275 = ($arguments273['value'] !== NULL ? $arguments273['value'] : $renderChildrenClosure274());

$output272 .= !is_string($value275) && !(is_object($value275) && method_exists($value275, '__toString')) ? $value275 : htmlspecialchars($value275, ($arguments273['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments273['encoding'], $arguments273['doubleEncode']);

$output272 .= '"';
$arguments271['then'] = $output272;
$arguments271['else'] = NULL;
$renderChildrenClosure276 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper277 = $self->getViewHelper('$viewHelper277', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper277->setArguments($arguments271);
$viewHelper277->setRenderingContext($renderingContext);
$viewHelper277->setRenderChildrenClosure($renderChildrenClosure276);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output264 .= $viewHelper277->initializeArgumentsAndRender();

$output264 .= '>
								<td class="check neos-priority1">
									<label for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments278 = array();
$arguments278['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments278['keepQuotes'] = false;
$arguments278['encoding'] = 'UTF-8';
$arguments278['doubleEncode'] = true;
$renderChildrenClosure279 = function() use ($renderingContext, $self) {
return NULL;
};
$value280 = ($arguments278['value'] !== NULL ? $arguments278['value'] : $renderChildrenClosure279());

$output264 .= !is_string($value280) && !(is_object($value280) && method_exists($value280, '__toString')) ? $value280 : htmlspecialchars($value280, ($arguments278['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments278['encoding'], $arguments278['doubleEncode']);

$output264 .= '" class="neos-checkbox">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Form\CheckboxViewHelper
$arguments281 = array();
$arguments281['name'] = 'packageKeys[]';
$arguments281['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments281['id'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments281['additionalAttributes'] = NULL;
$arguments281['data'] = NULL;
$arguments281['checked'] = NULL;
$arguments281['multiple'] = NULL;
$arguments281['property'] = NULL;
$arguments281['disabled'] = NULL;
$arguments281['errorClass'] = 'f3-form-error';
$arguments281['class'] = NULL;
$arguments281['dir'] = NULL;
$arguments281['lang'] = NULL;
$arguments281['style'] = NULL;
$arguments281['title'] = NULL;
$arguments281['accesskey'] = NULL;
$arguments281['tabindex'] = NULL;
$arguments281['onclick'] = NULL;
$renderChildrenClosure282 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper283 = $self->getViewHelper('$viewHelper283', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Form\CheckboxViewHelper');
$viewHelper283->setArguments($arguments281);
$viewHelper283->setRenderingContext($renderingContext);
$viewHelper283->setRenderChildrenClosure($renderChildrenClosure282);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Form\CheckboxViewHelper

$output264 .= $viewHelper283->initializeArgumentsAndRender();

$output264 .= '<span></span>
									</label>
								</td>
								<td class="package-name neos-priority1">
									<label for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments284 = array();
$arguments284['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments284['keepQuotes'] = false;
$arguments284['encoding'] = 'UTF-8';
$arguments284['doubleEncode'] = true;
$renderChildrenClosure285 = function() use ($renderingContext, $self) {
return NULL;
};
$value286 = ($arguments284['value'] !== NULL ? $arguments284['value'] : $renderChildrenClosure285());

$output264 .= !is_string($value286) && !(is_object($value286) && method_exists($value286, '__toString')) ? $value286 : htmlspecialchars($value286, ($arguments284['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments284['encoding'], $arguments284['doubleEncode']);

$output264 .= '">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments287 = array();
$arguments287['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.name', $renderingContext);
$arguments287['keepQuotes'] = false;
$arguments287['encoding'] = 'UTF-8';
$arguments287['doubleEncode'] = true;
$renderChildrenClosure288 = function() use ($renderingContext, $self) {
return NULL;
};
$value289 = ($arguments287['value'] !== NULL ? $arguments287['value'] : $renderChildrenClosure288());

$output264 .= !is_string($value289) && !(is_object($value289) && method_exists($value289, '__toString')) ? $value289 : htmlspecialchars($value289, ($arguments287['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments287['encoding'], $arguments287['doubleEncode']);

$output264 .= '
									</label>
								</td>
								<td class="package-version neos-priority2">
									<label for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments290 = array();
$arguments290['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments290['keepQuotes'] = false;
$arguments290['encoding'] = 'UTF-8';
$arguments290['doubleEncode'] = true;
$renderChildrenClosure291 = function() use ($renderingContext, $self) {
return NULL;
};
$value292 = ($arguments290['value'] !== NULL ? $arguments290['value'] : $renderChildrenClosure291());

$output264 .= !is_string($value292) && !(is_object($value292) && method_exists($value292, '__toString')) ? $value292 : htmlspecialchars($value292, ($arguments290['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments290['encoding'], $arguments290['doubleEncode']);

$output264 .= '">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments293 = array();
// Rendering Boolean node
$arguments293['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.version', $renderingContext));
$arguments293['then'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.version', $renderingContext);
$arguments293['else'] = '&nbsp;';
$renderChildrenClosure294 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper295 = $self->getViewHelper('$viewHelper295', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper295->setArguments($arguments293);
$viewHelper295->setRenderingContext($renderingContext);
$viewHelper295->setRenderChildrenClosure($renderChildrenClosure294);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output264 .= $viewHelper295->initializeArgumentsAndRender();

$output264 .= '
									</label>
								</td>
								<td class="package-key neos-priority3">
									<label for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments296 = array();
$arguments296['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments296['keepQuotes'] = false;
$arguments296['encoding'] = 'UTF-8';
$arguments296['doubleEncode'] = true;
$renderChildrenClosure297 = function() use ($renderingContext, $self) {
return NULL;
};
$value298 = ($arguments296['value'] !== NULL ? $arguments296['value'] : $renderChildrenClosure297());

$output264 .= !is_string($value298) && !(is_object($value298) && method_exists($value298, '__toString')) ? $value298 : htmlspecialchars($value298, ($arguments296['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments296['encoding'], $arguments296['doubleEncode']);

$output264 .= '">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments299 = array();
$arguments299['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments299['keepQuotes'] = false;
$arguments299['encoding'] = 'UTF-8';
$arguments299['doubleEncode'] = true;
$renderChildrenClosure300 = function() use ($renderingContext, $self) {
return NULL;
};
$value301 = ($arguments299['value'] !== NULL ? $arguments299['value'] : $renderChildrenClosure300());

$output264 .= !is_string($value301) && !(is_object($value301) && method_exists($value301, '__toString')) ? $value301 : htmlspecialchars($value301, ($arguments299['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments299['encoding'], $arguments299['doubleEncode']);

$output264 .= '
									</label>
								</td>
								<td class="package-type neos-priority3">
									<label for="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments302 = array();
$arguments302['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments302['keepQuotes'] = false;
$arguments302['encoding'] = 'UTF-8';
$arguments302['doubleEncode'] = true;
$renderChildrenClosure303 = function() use ($renderingContext, $self) {
return NULL;
};
$value304 = ($arguments302['value'] !== NULL ? $arguments302['value'] : $renderChildrenClosure303());

$output264 .= !is_string($value304) && !(is_object($value304) && method_exists($value304, '__toString')) ? $value304 : htmlspecialchars($value304, ($arguments302['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments302['encoding'], $arguments302['doubleEncode']);

$output264 .= '">
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments305 = array();
$arguments305['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.type', $renderingContext);
$arguments305['keepQuotes'] = false;
$arguments305['encoding'] = 'UTF-8';
$arguments305['doubleEncode'] = true;
$renderChildrenClosure306 = function() use ($renderingContext, $self) {
return NULL;
};
$value307 = ($arguments305['value'] !== NULL ? $arguments305['value'] : $renderChildrenClosure306());

$output264 .= !is_string($value307) && !(is_object($value307) && method_exists($value307, '__toString')) ? $value307 : htmlspecialchars($value307, ($arguments305['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments305['encoding'], $arguments305['doubleEncode']);

$output264 .= '
									</label>
								</td>
								<td class="neos-action neos-priority1">
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments308 = array();
// Rendering Boolean node
$arguments308['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isActive', $renderingContext));
$arguments308['then'] = NULL;
$arguments308['else'] = NULL;
$renderChildrenClosure309 = function() use ($renderingContext, $self) {
$output310 = '';

$output310 .= '
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments311 = array();
$renderChildrenClosure312 = function() use ($renderingContext, $self) {
$output313 = '';

$output313 .= '
											';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments314 = array();
// Rendering Boolean node
$arguments314['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'isDevelopmentContext', $renderingContext));
$arguments314['then'] = NULL;
$arguments314['else'] = NULL;
$renderChildrenClosure315 = function() use ($renderingContext, $self) {
$output316 = '';

$output316 .= '
												';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments317 = array();
// Rendering Boolean node
$arguments317['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isFrozen', $renderingContext));
$arguments317['then'] = NULL;
$arguments317['else'] = NULL;
$renderChildrenClosure318 = function() use ($renderingContext, $self) {
$output319 = '';

$output319 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments320 = array();
$renderChildrenClosure321 = function() use ($renderingContext, $self) {
$output322 = '';

$output322 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments323 = array();
$arguments323['action'] = 'unfreeze';
$arguments323['class'] = 'neos-button neos-button-freeze neos-active';
// Rendering Array
$array324 = array();
$array324['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments323['arguments'] = $array324;
$arguments323['title'] = 'This package is currently frozen. Click here to unfreeze it.';
$arguments323['additionalAttributes'] = NULL;
$arguments323['data'] = NULL;
$arguments323['controller'] = NULL;
$arguments323['package'] = NULL;
$arguments323['subpackage'] = NULL;
$arguments323['section'] = '';
$arguments323['format'] = '';
$arguments323['additionalParams'] = array (
);
$arguments323['addQueryString'] = false;
$arguments323['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments323['useParentRequest'] = false;
$arguments323['absolute'] = true;
$arguments323['dir'] = NULL;
$arguments323['id'] = NULL;
$arguments323['lang'] = NULL;
$arguments323['style'] = NULL;
$arguments323['accesskey'] = NULL;
$arguments323['tabindex'] = NULL;
$arguments323['onclick'] = NULL;
$arguments323['name'] = NULL;
$arguments323['rel'] = NULL;
$arguments323['rev'] = NULL;
$arguments323['target'] = NULL;
$renderChildrenClosure325 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper326 = $self->getViewHelper('$viewHelper326', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper326->setArguments($arguments323);
$viewHelper326->setRenderingContext($renderingContext);
$viewHelper326->setRenderChildrenClosure($renderChildrenClosure325);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output322 .= $viewHelper326->initializeArgumentsAndRender();

$output322 .= '
													';
return $output322;
};
$viewHelper327 = $self->getViewHelper('$viewHelper327', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper327->setArguments($arguments320);
$viewHelper327->setRenderingContext($renderingContext);
$viewHelper327->setRenderChildrenClosure($renderChildrenClosure321);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output319 .= $viewHelper327->initializeArgumentsAndRender();

$output319 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments328 = array();
$renderChildrenClosure329 = function() use ($renderingContext, $self) {
$output330 = '';

$output330 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments331 = array();
$arguments331['action'] = 'freeze';
$arguments331['class'] = 'neos-button neos-button-freeze';
// Rendering Array
$array332 = array();
$array332['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments331['arguments'] = $array332;
$arguments331['title'] = 'Click here to freeze the package in order to speed up your website.';
$arguments331['additionalAttributes'] = NULL;
$arguments331['data'] = NULL;
$arguments331['controller'] = NULL;
$arguments331['package'] = NULL;
$arguments331['subpackage'] = NULL;
$arguments331['section'] = '';
$arguments331['format'] = '';
$arguments331['additionalParams'] = array (
);
$arguments331['addQueryString'] = false;
$arguments331['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments331['useParentRequest'] = false;
$arguments331['absolute'] = true;
$arguments331['dir'] = NULL;
$arguments331['id'] = NULL;
$arguments331['lang'] = NULL;
$arguments331['style'] = NULL;
$arguments331['accesskey'] = NULL;
$arguments331['tabindex'] = NULL;
$arguments331['onclick'] = NULL;
$arguments331['name'] = NULL;
$arguments331['rel'] = NULL;
$arguments331['rev'] = NULL;
$arguments331['target'] = NULL;
$renderChildrenClosure333 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper334 = $self->getViewHelper('$viewHelper334', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper334->setArguments($arguments331);
$viewHelper334->setRenderingContext($renderingContext);
$viewHelper334->setRenderChildrenClosure($renderChildrenClosure333);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output330 .= $viewHelper334->initializeArgumentsAndRender();

$output330 .= '
													';
return $output330;
};
$viewHelper335 = $self->getViewHelper('$viewHelper335', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper335->setArguments($arguments328);
$viewHelper335->setRenderingContext($renderingContext);
$viewHelper335->setRenderChildrenClosure($renderChildrenClosure329);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output319 .= $viewHelper335->initializeArgumentsAndRender();

$output319 .= '
												';
return $output319;
};
$arguments317['__thenClosure'] = function() use ($renderingContext, $self) {
$output336 = '';

$output336 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments337 = array();
$arguments337['action'] = 'unfreeze';
$arguments337['class'] = 'neos-button neos-button-freeze neos-active';
// Rendering Array
$array338 = array();
$array338['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments337['arguments'] = $array338;
$arguments337['title'] = 'This package is currently frozen. Click here to unfreeze it.';
$arguments337['additionalAttributes'] = NULL;
$arguments337['data'] = NULL;
$arguments337['controller'] = NULL;
$arguments337['package'] = NULL;
$arguments337['subpackage'] = NULL;
$arguments337['section'] = '';
$arguments337['format'] = '';
$arguments337['additionalParams'] = array (
);
$arguments337['addQueryString'] = false;
$arguments337['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments337['useParentRequest'] = false;
$arguments337['absolute'] = true;
$arguments337['dir'] = NULL;
$arguments337['id'] = NULL;
$arguments337['lang'] = NULL;
$arguments337['style'] = NULL;
$arguments337['accesskey'] = NULL;
$arguments337['tabindex'] = NULL;
$arguments337['onclick'] = NULL;
$arguments337['name'] = NULL;
$arguments337['rel'] = NULL;
$arguments337['rev'] = NULL;
$arguments337['target'] = NULL;
$renderChildrenClosure339 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper340 = $self->getViewHelper('$viewHelper340', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper340->setArguments($arguments337);
$viewHelper340->setRenderingContext($renderingContext);
$viewHelper340->setRenderChildrenClosure($renderChildrenClosure339);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output336 .= $viewHelper340->initializeArgumentsAndRender();

$output336 .= '
													';
return $output336;
};
$arguments317['__elseClosure'] = function() use ($renderingContext, $self) {
$output341 = '';

$output341 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments342 = array();
$arguments342['action'] = 'freeze';
$arguments342['class'] = 'neos-button neos-button-freeze';
// Rendering Array
$array343 = array();
$array343['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments342['arguments'] = $array343;
$arguments342['title'] = 'Click here to freeze the package in order to speed up your website.';
$arguments342['additionalAttributes'] = NULL;
$arguments342['data'] = NULL;
$arguments342['controller'] = NULL;
$arguments342['package'] = NULL;
$arguments342['subpackage'] = NULL;
$arguments342['section'] = '';
$arguments342['format'] = '';
$arguments342['additionalParams'] = array (
);
$arguments342['addQueryString'] = false;
$arguments342['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments342['useParentRequest'] = false;
$arguments342['absolute'] = true;
$arguments342['dir'] = NULL;
$arguments342['id'] = NULL;
$arguments342['lang'] = NULL;
$arguments342['style'] = NULL;
$arguments342['accesskey'] = NULL;
$arguments342['tabindex'] = NULL;
$arguments342['onclick'] = NULL;
$arguments342['name'] = NULL;
$arguments342['rel'] = NULL;
$arguments342['rev'] = NULL;
$arguments342['target'] = NULL;
$renderChildrenClosure344 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper345 = $self->getViewHelper('$viewHelper345', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper345->setArguments($arguments342);
$viewHelper345->setRenderingContext($renderingContext);
$viewHelper345->setRenderChildrenClosure($renderChildrenClosure344);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output341 .= $viewHelper345->initializeArgumentsAndRender();

$output341 .= '
													';
return $output341;
};
$viewHelper346 = $self->getViewHelper('$viewHelper346', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper346->setArguments($arguments317);
$viewHelper346->setRenderingContext($renderingContext);
$viewHelper346->setRenderChildrenClosure($renderChildrenClosure318);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output316 .= $viewHelper346->initializeArgumentsAndRender();

$output316 .= '
											';
return $output316;
};
$viewHelper347 = $self->getViewHelper('$viewHelper347', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper347->setArguments($arguments314);
$viewHelper347->setRenderingContext($renderingContext);
$viewHelper347->setRenderChildrenClosure($renderChildrenClosure315);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output313 .= $viewHelper347->initializeArgumentsAndRender();

$output313 .= '
											';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments348 = array();
// Rendering Boolean node
$arguments348['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isProtected', $renderingContext));
$arguments348['then'] = NULL;
$arguments348['else'] = NULL;
$renderChildrenClosure349 = function() use ($renderingContext, $self) {
$output350 = '';

$output350 .= '
												';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments351 = array();
$renderChildrenClosure352 = function() use ($renderingContext, $self) {
return '
													<button class="neos-button neos-button-warning neos-disabled" title="This package is protected and cannot be deactivated." disabled="disabled">
														<i class="icon-pause icon-white"></i>
													</button>
												';
};
$viewHelper353 = $self->getViewHelper('$viewHelper353', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper353->setArguments($arguments351);
$viewHelper353->setRenderingContext($renderingContext);
$viewHelper353->setRenderChildrenClosure($renderChildrenClosure352);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output350 .= $viewHelper353->initializeArgumentsAndRender();

$output350 .= '
												';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments354 = array();
$renderChildrenClosure355 = function() use ($renderingContext, $self) {
$output356 = '';

$output356 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments357 = array();
$arguments357['action'] = 'deactivate';
$arguments357['class'] = 'neos-button neos-button-warning';
// Rendering Array
$array358 = array();
$array358['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments357['arguments'] = $array358;
$output359 = '';

$output359 .= 'Click to deactivate ';

$output359 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);

$output359 .= '.';
$arguments357['title'] = $output359;
$arguments357['additionalAttributes'] = NULL;
$arguments357['data'] = NULL;
$arguments357['controller'] = NULL;
$arguments357['package'] = NULL;
$arguments357['subpackage'] = NULL;
$arguments357['section'] = '';
$arguments357['format'] = '';
$arguments357['additionalParams'] = array (
);
$arguments357['addQueryString'] = false;
$arguments357['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments357['useParentRequest'] = false;
$arguments357['absolute'] = true;
$arguments357['dir'] = NULL;
$arguments357['id'] = NULL;
$arguments357['lang'] = NULL;
$arguments357['style'] = NULL;
$arguments357['accesskey'] = NULL;
$arguments357['tabindex'] = NULL;
$arguments357['onclick'] = NULL;
$arguments357['name'] = NULL;
$arguments357['rel'] = NULL;
$arguments357['rev'] = NULL;
$arguments357['target'] = NULL;
$renderChildrenClosure360 = function() use ($renderingContext, $self) {
return '
														<i class="icon-pause icon-white"></i>
													';
};
$viewHelper361 = $self->getViewHelper('$viewHelper361', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper361->setArguments($arguments357);
$viewHelper361->setRenderingContext($renderingContext);
$viewHelper361->setRenderChildrenClosure($renderChildrenClosure360);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output356 .= $viewHelper361->initializeArgumentsAndRender();

$output356 .= '
												';
return $output356;
};
$viewHelper362 = $self->getViewHelper('$viewHelper362', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper362->setArguments($arguments354);
$viewHelper362->setRenderingContext($renderingContext);
$viewHelper362->setRenderChildrenClosure($renderChildrenClosure355);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output350 .= $viewHelper362->initializeArgumentsAndRender();

$output350 .= '
											';
return $output350;
};
$arguments348['__thenClosure'] = function() use ($renderingContext, $self) {
return '
													<button class="neos-button neos-button-warning neos-disabled" title="This package is protected and cannot be deactivated." disabled="disabled">
														<i class="icon-pause icon-white"></i>
													</button>
												';
};
$arguments348['__elseClosure'] = function() use ($renderingContext, $self) {
$output363 = '';

$output363 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments364 = array();
$arguments364['action'] = 'deactivate';
$arguments364['class'] = 'neos-button neos-button-warning';
// Rendering Array
$array365 = array();
$array365['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments364['arguments'] = $array365;
$output366 = '';

$output366 .= 'Click to deactivate ';

$output366 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);

$output366 .= '.';
$arguments364['title'] = $output366;
$arguments364['additionalAttributes'] = NULL;
$arguments364['data'] = NULL;
$arguments364['controller'] = NULL;
$arguments364['package'] = NULL;
$arguments364['subpackage'] = NULL;
$arguments364['section'] = '';
$arguments364['format'] = '';
$arguments364['additionalParams'] = array (
);
$arguments364['addQueryString'] = false;
$arguments364['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments364['useParentRequest'] = false;
$arguments364['absolute'] = true;
$arguments364['dir'] = NULL;
$arguments364['id'] = NULL;
$arguments364['lang'] = NULL;
$arguments364['style'] = NULL;
$arguments364['accesskey'] = NULL;
$arguments364['tabindex'] = NULL;
$arguments364['onclick'] = NULL;
$arguments364['name'] = NULL;
$arguments364['rel'] = NULL;
$arguments364['rev'] = NULL;
$arguments364['target'] = NULL;
$renderChildrenClosure367 = function() use ($renderingContext, $self) {
return '
														<i class="icon-pause icon-white"></i>
													';
};
$viewHelper368 = $self->getViewHelper('$viewHelper368', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper368->setArguments($arguments364);
$viewHelper368->setRenderingContext($renderingContext);
$viewHelper368->setRenderChildrenClosure($renderChildrenClosure367);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output363 .= $viewHelper368->initializeArgumentsAndRender();

$output363 .= '
												';
return $output363;
};
$viewHelper369 = $self->getViewHelper('$viewHelper369', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper369->setArguments($arguments348);
$viewHelper369->setRenderingContext($renderingContext);
$viewHelper369->setRenderChildrenClosure($renderChildrenClosure349);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output313 .= $viewHelper369->initializeArgumentsAndRender();

$output313 .= '
										';
return $output313;
};
$viewHelper370 = $self->getViewHelper('$viewHelper370', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper370->setArguments($arguments311);
$viewHelper370->setRenderingContext($renderingContext);
$viewHelper370->setRenderChildrenClosure($renderChildrenClosure312);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output310 .= $viewHelper370->initializeArgumentsAndRender();

$output310 .= '
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments371 = array();
$renderChildrenClosure372 = function() use ($renderingContext, $self) {
$output373 = '';

$output373 .= '
											';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments374 = array();
$arguments374['action'] = 'activate';
$arguments374['class'] = 'neos-button neos-button-success';
// Rendering Array
$array375 = array();
$array375['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments374['arguments'] = $array375;
$output376 = '';

$output376 .= 'Click to activate ';

$output376 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageName', $renderingContext);

$output376 .= '.';
$arguments374['title'] = $output376;
$arguments374['additionalAttributes'] = NULL;
$arguments374['data'] = NULL;
$arguments374['controller'] = NULL;
$arguments374['package'] = NULL;
$arguments374['subpackage'] = NULL;
$arguments374['section'] = '';
$arguments374['format'] = '';
$arguments374['additionalParams'] = array (
);
$arguments374['addQueryString'] = false;
$arguments374['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments374['useParentRequest'] = false;
$arguments374['absolute'] = true;
$arguments374['dir'] = NULL;
$arguments374['id'] = NULL;
$arguments374['lang'] = NULL;
$arguments374['style'] = NULL;
$arguments374['accesskey'] = NULL;
$arguments374['tabindex'] = NULL;
$arguments374['onclick'] = NULL;
$arguments374['name'] = NULL;
$arguments374['rel'] = NULL;
$arguments374['rev'] = NULL;
$arguments374['target'] = NULL;
$renderChildrenClosure377 = function() use ($renderingContext, $self) {
return '
												<i class="icon-play icon-white"></i>
											';
};
$viewHelper378 = $self->getViewHelper('$viewHelper378', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper378->setArguments($arguments374);
$viewHelper378->setRenderingContext($renderingContext);
$viewHelper378->setRenderChildrenClosure($renderChildrenClosure377);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output373 .= $viewHelper378->initializeArgumentsAndRender();

$output373 .= '
										';
return $output373;
};
$viewHelper379 = $self->getViewHelper('$viewHelper379', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper379->setArguments($arguments371);
$viewHelper379->setRenderingContext($renderingContext);
$viewHelper379->setRenderChildrenClosure($renderChildrenClosure372);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output310 .= $viewHelper379->initializeArgumentsAndRender();

$output310 .= '
									';
return $output310;
};
$arguments308['__thenClosure'] = function() use ($renderingContext, $self) {
$output380 = '';

$output380 .= '
											';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments381 = array();
// Rendering Boolean node
$arguments381['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'isDevelopmentContext', $renderingContext));
$arguments381['then'] = NULL;
$arguments381['else'] = NULL;
$renderChildrenClosure382 = function() use ($renderingContext, $self) {
$output383 = '';

$output383 .= '
												';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments384 = array();
// Rendering Boolean node
$arguments384['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isFrozen', $renderingContext));
$arguments384['then'] = NULL;
$arguments384['else'] = NULL;
$renderChildrenClosure385 = function() use ($renderingContext, $self) {
$output386 = '';

$output386 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments387 = array();
$renderChildrenClosure388 = function() use ($renderingContext, $self) {
$output389 = '';

$output389 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments390 = array();
$arguments390['action'] = 'unfreeze';
$arguments390['class'] = 'neos-button neos-button-freeze neos-active';
// Rendering Array
$array391 = array();
$array391['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments390['arguments'] = $array391;
$arguments390['title'] = 'This package is currently frozen. Click here to unfreeze it.';
$arguments390['additionalAttributes'] = NULL;
$arguments390['data'] = NULL;
$arguments390['controller'] = NULL;
$arguments390['package'] = NULL;
$arguments390['subpackage'] = NULL;
$arguments390['section'] = '';
$arguments390['format'] = '';
$arguments390['additionalParams'] = array (
);
$arguments390['addQueryString'] = false;
$arguments390['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments390['useParentRequest'] = false;
$arguments390['absolute'] = true;
$arguments390['dir'] = NULL;
$arguments390['id'] = NULL;
$arguments390['lang'] = NULL;
$arguments390['style'] = NULL;
$arguments390['accesskey'] = NULL;
$arguments390['tabindex'] = NULL;
$arguments390['onclick'] = NULL;
$arguments390['name'] = NULL;
$arguments390['rel'] = NULL;
$arguments390['rev'] = NULL;
$arguments390['target'] = NULL;
$renderChildrenClosure392 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper393 = $self->getViewHelper('$viewHelper393', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper393->setArguments($arguments390);
$viewHelper393->setRenderingContext($renderingContext);
$viewHelper393->setRenderChildrenClosure($renderChildrenClosure392);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output389 .= $viewHelper393->initializeArgumentsAndRender();

$output389 .= '
													';
return $output389;
};
$viewHelper394 = $self->getViewHelper('$viewHelper394', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper394->setArguments($arguments387);
$viewHelper394->setRenderingContext($renderingContext);
$viewHelper394->setRenderChildrenClosure($renderChildrenClosure388);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output386 .= $viewHelper394->initializeArgumentsAndRender();

$output386 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments395 = array();
$renderChildrenClosure396 = function() use ($renderingContext, $self) {
$output397 = '';

$output397 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments398 = array();
$arguments398['action'] = 'freeze';
$arguments398['class'] = 'neos-button neos-button-freeze';
// Rendering Array
$array399 = array();
$array399['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments398['arguments'] = $array399;
$arguments398['title'] = 'Click here to freeze the package in order to speed up your website.';
$arguments398['additionalAttributes'] = NULL;
$arguments398['data'] = NULL;
$arguments398['controller'] = NULL;
$arguments398['package'] = NULL;
$arguments398['subpackage'] = NULL;
$arguments398['section'] = '';
$arguments398['format'] = '';
$arguments398['additionalParams'] = array (
);
$arguments398['addQueryString'] = false;
$arguments398['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments398['useParentRequest'] = false;
$arguments398['absolute'] = true;
$arguments398['dir'] = NULL;
$arguments398['id'] = NULL;
$arguments398['lang'] = NULL;
$arguments398['style'] = NULL;
$arguments398['accesskey'] = NULL;
$arguments398['tabindex'] = NULL;
$arguments398['onclick'] = NULL;
$arguments398['name'] = NULL;
$arguments398['rel'] = NULL;
$arguments398['rev'] = NULL;
$arguments398['target'] = NULL;
$renderChildrenClosure400 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper401 = $self->getViewHelper('$viewHelper401', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper401->setArguments($arguments398);
$viewHelper401->setRenderingContext($renderingContext);
$viewHelper401->setRenderChildrenClosure($renderChildrenClosure400);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output397 .= $viewHelper401->initializeArgumentsAndRender();

$output397 .= '
													';
return $output397;
};
$viewHelper402 = $self->getViewHelper('$viewHelper402', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper402->setArguments($arguments395);
$viewHelper402->setRenderingContext($renderingContext);
$viewHelper402->setRenderChildrenClosure($renderChildrenClosure396);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output386 .= $viewHelper402->initializeArgumentsAndRender();

$output386 .= '
												';
return $output386;
};
$arguments384['__thenClosure'] = function() use ($renderingContext, $self) {
$output403 = '';

$output403 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments404 = array();
$arguments404['action'] = 'unfreeze';
$arguments404['class'] = 'neos-button neos-button-freeze neos-active';
// Rendering Array
$array405 = array();
$array405['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments404['arguments'] = $array405;
$arguments404['title'] = 'This package is currently frozen. Click here to unfreeze it.';
$arguments404['additionalAttributes'] = NULL;
$arguments404['data'] = NULL;
$arguments404['controller'] = NULL;
$arguments404['package'] = NULL;
$arguments404['subpackage'] = NULL;
$arguments404['section'] = '';
$arguments404['format'] = '';
$arguments404['additionalParams'] = array (
);
$arguments404['addQueryString'] = false;
$arguments404['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments404['useParentRequest'] = false;
$arguments404['absolute'] = true;
$arguments404['dir'] = NULL;
$arguments404['id'] = NULL;
$arguments404['lang'] = NULL;
$arguments404['style'] = NULL;
$arguments404['accesskey'] = NULL;
$arguments404['tabindex'] = NULL;
$arguments404['onclick'] = NULL;
$arguments404['name'] = NULL;
$arguments404['rel'] = NULL;
$arguments404['rev'] = NULL;
$arguments404['target'] = NULL;
$renderChildrenClosure406 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper407 = $self->getViewHelper('$viewHelper407', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper407->setArguments($arguments404);
$viewHelper407->setRenderingContext($renderingContext);
$viewHelper407->setRenderChildrenClosure($renderChildrenClosure406);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output403 .= $viewHelper407->initializeArgumentsAndRender();

$output403 .= '
													';
return $output403;
};
$arguments384['__elseClosure'] = function() use ($renderingContext, $self) {
$output408 = '';

$output408 .= '
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments409 = array();
$arguments409['action'] = 'freeze';
$arguments409['class'] = 'neos-button neos-button-freeze';
// Rendering Array
$array410 = array();
$array410['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments409['arguments'] = $array410;
$arguments409['title'] = 'Click here to freeze the package in order to speed up your website.';
$arguments409['additionalAttributes'] = NULL;
$arguments409['data'] = NULL;
$arguments409['controller'] = NULL;
$arguments409['package'] = NULL;
$arguments409['subpackage'] = NULL;
$arguments409['section'] = '';
$arguments409['format'] = '';
$arguments409['additionalParams'] = array (
);
$arguments409['addQueryString'] = false;
$arguments409['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments409['useParentRequest'] = false;
$arguments409['absolute'] = true;
$arguments409['dir'] = NULL;
$arguments409['id'] = NULL;
$arguments409['lang'] = NULL;
$arguments409['style'] = NULL;
$arguments409['accesskey'] = NULL;
$arguments409['tabindex'] = NULL;
$arguments409['onclick'] = NULL;
$arguments409['name'] = NULL;
$arguments409['rel'] = NULL;
$arguments409['rev'] = NULL;
$arguments409['target'] = NULL;
$renderChildrenClosure411 = function() use ($renderingContext, $self) {
return '
															<i class="icon-asterisk icon-white"></i>
														';
};
$viewHelper412 = $self->getViewHelper('$viewHelper412', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper412->setArguments($arguments409);
$viewHelper412->setRenderingContext($renderingContext);
$viewHelper412->setRenderChildrenClosure($renderChildrenClosure411);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output408 .= $viewHelper412->initializeArgumentsAndRender();

$output408 .= '
													';
return $output408;
};
$viewHelper413 = $self->getViewHelper('$viewHelper413', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper413->setArguments($arguments384);
$viewHelper413->setRenderingContext($renderingContext);
$viewHelper413->setRenderChildrenClosure($renderChildrenClosure385);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output383 .= $viewHelper413->initializeArgumentsAndRender();

$output383 .= '
											';
return $output383;
};
$viewHelper414 = $self->getViewHelper('$viewHelper414', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper414->setArguments($arguments381);
$viewHelper414->setRenderingContext($renderingContext);
$viewHelper414->setRenderChildrenClosure($renderChildrenClosure382);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output380 .= $viewHelper414->initializeArgumentsAndRender();

$output380 .= '
											';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments415 = array();
// Rendering Boolean node
$arguments415['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isProtected', $renderingContext));
$arguments415['then'] = NULL;
$arguments415['else'] = NULL;
$renderChildrenClosure416 = function() use ($renderingContext, $self) {
$output417 = '';

$output417 .= '
												';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments418 = array();
$renderChildrenClosure419 = function() use ($renderingContext, $self) {
return '
													<button class="neos-button neos-button-warning neos-disabled" title="This package is protected and cannot be deactivated." disabled="disabled">
														<i class="icon-pause icon-white"></i>
													</button>
												';
};
$viewHelper420 = $self->getViewHelper('$viewHelper420', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper420->setArguments($arguments418);
$viewHelper420->setRenderingContext($renderingContext);
$viewHelper420->setRenderChildrenClosure($renderChildrenClosure419);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output417 .= $viewHelper420->initializeArgumentsAndRender();

$output417 .= '
												';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments421 = array();
$renderChildrenClosure422 = function() use ($renderingContext, $self) {
$output423 = '';

$output423 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments424 = array();
$arguments424['action'] = 'deactivate';
$arguments424['class'] = 'neos-button neos-button-warning';
// Rendering Array
$array425 = array();
$array425['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments424['arguments'] = $array425;
$output426 = '';

$output426 .= 'Click to deactivate ';

$output426 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);

$output426 .= '.';
$arguments424['title'] = $output426;
$arguments424['additionalAttributes'] = NULL;
$arguments424['data'] = NULL;
$arguments424['controller'] = NULL;
$arguments424['package'] = NULL;
$arguments424['subpackage'] = NULL;
$arguments424['section'] = '';
$arguments424['format'] = '';
$arguments424['additionalParams'] = array (
);
$arguments424['addQueryString'] = false;
$arguments424['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments424['useParentRequest'] = false;
$arguments424['absolute'] = true;
$arguments424['dir'] = NULL;
$arguments424['id'] = NULL;
$arguments424['lang'] = NULL;
$arguments424['style'] = NULL;
$arguments424['accesskey'] = NULL;
$arguments424['tabindex'] = NULL;
$arguments424['onclick'] = NULL;
$arguments424['name'] = NULL;
$arguments424['rel'] = NULL;
$arguments424['rev'] = NULL;
$arguments424['target'] = NULL;
$renderChildrenClosure427 = function() use ($renderingContext, $self) {
return '
														<i class="icon-pause icon-white"></i>
													';
};
$viewHelper428 = $self->getViewHelper('$viewHelper428', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper428->setArguments($arguments424);
$viewHelper428->setRenderingContext($renderingContext);
$viewHelper428->setRenderChildrenClosure($renderChildrenClosure427);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output423 .= $viewHelper428->initializeArgumentsAndRender();

$output423 .= '
												';
return $output423;
};
$viewHelper429 = $self->getViewHelper('$viewHelper429', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper429->setArguments($arguments421);
$viewHelper429->setRenderingContext($renderingContext);
$viewHelper429->setRenderChildrenClosure($renderChildrenClosure422);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output417 .= $viewHelper429->initializeArgumentsAndRender();

$output417 .= '
											';
return $output417;
};
$arguments415['__thenClosure'] = function() use ($renderingContext, $self) {
return '
													<button class="neos-button neos-button-warning neos-disabled" title="This package is protected and cannot be deactivated." disabled="disabled">
														<i class="icon-pause icon-white"></i>
													</button>
												';
};
$arguments415['__elseClosure'] = function() use ($renderingContext, $self) {
$output430 = '';

$output430 .= '
													';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments431 = array();
$arguments431['action'] = 'deactivate';
$arguments431['class'] = 'neos-button neos-button-warning';
// Rendering Array
$array432 = array();
$array432['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments431['arguments'] = $array432;
$output433 = '';

$output433 .= 'Click to deactivate ';

$output433 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);

$output433 .= '.';
$arguments431['title'] = $output433;
$arguments431['additionalAttributes'] = NULL;
$arguments431['data'] = NULL;
$arguments431['controller'] = NULL;
$arguments431['package'] = NULL;
$arguments431['subpackage'] = NULL;
$arguments431['section'] = '';
$arguments431['format'] = '';
$arguments431['additionalParams'] = array (
);
$arguments431['addQueryString'] = false;
$arguments431['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments431['useParentRequest'] = false;
$arguments431['absolute'] = true;
$arguments431['dir'] = NULL;
$arguments431['id'] = NULL;
$arguments431['lang'] = NULL;
$arguments431['style'] = NULL;
$arguments431['accesskey'] = NULL;
$arguments431['tabindex'] = NULL;
$arguments431['onclick'] = NULL;
$arguments431['name'] = NULL;
$arguments431['rel'] = NULL;
$arguments431['rev'] = NULL;
$arguments431['target'] = NULL;
$renderChildrenClosure434 = function() use ($renderingContext, $self) {
return '
														<i class="icon-pause icon-white"></i>
													';
};
$viewHelper435 = $self->getViewHelper('$viewHelper435', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper435->setArguments($arguments431);
$viewHelper435->setRenderingContext($renderingContext);
$viewHelper435->setRenderChildrenClosure($renderChildrenClosure434);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output430 .= $viewHelper435->initializeArgumentsAndRender();

$output430 .= '
												';
return $output430;
};
$viewHelper436 = $self->getViewHelper('$viewHelper436', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper436->setArguments($arguments415);
$viewHelper436->setRenderingContext($renderingContext);
$viewHelper436->setRenderChildrenClosure($renderChildrenClosure416);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output380 .= $viewHelper436->initializeArgumentsAndRender();

$output380 .= '
										';
return $output380;
};
$arguments308['__elseClosure'] = function() use ($renderingContext, $self) {
$output437 = '';

$output437 .= '
											';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments438 = array();
$arguments438['action'] = 'activate';
$arguments438['class'] = 'neos-button neos-button-success';
// Rendering Array
$array439 = array();
$array439['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments438['arguments'] = $array439;
$output440 = '';

$output440 .= 'Click to activate ';

$output440 .= \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageName', $renderingContext);

$output440 .= '.';
$arguments438['title'] = $output440;
$arguments438['additionalAttributes'] = NULL;
$arguments438['data'] = NULL;
$arguments438['controller'] = NULL;
$arguments438['package'] = NULL;
$arguments438['subpackage'] = NULL;
$arguments438['section'] = '';
$arguments438['format'] = '';
$arguments438['additionalParams'] = array (
);
$arguments438['addQueryString'] = false;
$arguments438['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments438['useParentRequest'] = false;
$arguments438['absolute'] = true;
$arguments438['dir'] = NULL;
$arguments438['id'] = NULL;
$arguments438['lang'] = NULL;
$arguments438['style'] = NULL;
$arguments438['accesskey'] = NULL;
$arguments438['tabindex'] = NULL;
$arguments438['onclick'] = NULL;
$arguments438['name'] = NULL;
$arguments438['rel'] = NULL;
$arguments438['rev'] = NULL;
$arguments438['target'] = NULL;
$renderChildrenClosure441 = function() use ($renderingContext, $self) {
return '
												<i class="icon-play icon-white"></i>
											';
};
$viewHelper442 = $self->getViewHelper('$viewHelper442', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper442->setArguments($arguments438);
$viewHelper442->setRenderingContext($renderingContext);
$viewHelper442->setRenderChildrenClosure($renderChildrenClosure441);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output437 .= $viewHelper442->initializeArgumentsAndRender();

$output437 .= '
										';
return $output437;
};
$viewHelper443 = $self->getViewHelper('$viewHelper443', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper443->setArguments($arguments308);
$viewHelper443->setRenderingContext($renderingContext);
$viewHelper443->setRenderChildrenClosure($renderChildrenClosure309);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output264 .= $viewHelper443->initializeArgumentsAndRender();

$output264 .= '
									';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments444 = array();
// Rendering Boolean node
$arguments444['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.isProtected', $renderingContext));
$arguments444['then'] = NULL;
$arguments444['else'] = NULL;
$renderChildrenClosure445 = function() use ($renderingContext, $self) {
$output446 = '';

$output446 .= '
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper
$arguments447 = array();
$renderChildrenClosure448 = function() use ($renderingContext, $self) {
return '
											<button class="neos-button neos-button-danger neos-disabled" title="This package is protected and cannot be deleted." disabled="disabled"><i class="icon-trash icon-white"></i></button>
										';
};
$viewHelper449 = $self->getViewHelper('$viewHelper449', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ThenViewHelper');
$viewHelper449->setArguments($arguments447);
$viewHelper449->setRenderingContext($renderingContext);
$viewHelper449->setRenderChildrenClosure($renderChildrenClosure448);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ThenViewHelper

$output446 .= $viewHelper449->initializeArgumentsAndRender();

$output446 .= '
										';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper
$arguments450 = array();
$renderChildrenClosure451 = function() use ($renderingContext, $self) {
$output452 = '';

$output452 .= '
											<button class="neos-button neos-button-danger" title="Click to delete ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments453 = array();
$arguments453['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments453['keepQuotes'] = false;
$arguments453['encoding'] = 'UTF-8';
$arguments453['doubleEncode'] = true;
$renderChildrenClosure454 = function() use ($renderingContext, $self) {
return NULL;
};
$value455 = ($arguments453['value'] !== NULL ? $arguments453['value'] : $renderChildrenClosure454());

$output452 .= !is_string($value455) && !(is_object($value455) && method_exists($value455, '__toString')) ? $value455 : htmlspecialchars($value455, ($arguments453['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments453['encoding'], $arguments453['doubleEncode']);

$output452 .= '." data-toggle="modal" href="#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments456 = array();
$arguments456['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.sanitizedPackageKey', $renderingContext);
$arguments456['keepQuotes'] = false;
$arguments456['encoding'] = 'UTF-8';
$arguments456['doubleEncode'] = true;
$renderChildrenClosure457 = function() use ($renderingContext, $self) {
return NULL;
};
$value458 = ($arguments456['value'] !== NULL ? $arguments456['value'] : $renderChildrenClosure457());

$output452 .= !is_string($value458) && !(is_object($value458) && method_exists($value458, '__toString')) ? $value458 : htmlspecialchars($value458, ($arguments456['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments456['encoding'], $arguments456['doubleEncode']);

$output452 .= '"><i class="icon-trash icon-white"></i></button>
											<div class="neos-hide" id="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments459 = array();
$arguments459['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.sanitizedPackageKey', $renderingContext);
$arguments459['keepQuotes'] = false;
$arguments459['encoding'] = 'UTF-8';
$arguments459['doubleEncode'] = true;
$renderChildrenClosure460 = function() use ($renderingContext, $self) {
return NULL;
};
$value461 = ($arguments459['value'] !== NULL ? $arguments459['value'] : $renderChildrenClosure460());

$output452 .= !is_string($value461) && !(is_object($value461) && method_exists($value461, '__toString')) ? $value461 : htmlspecialchars($value461, ($arguments459['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments459['encoding'], $arguments459['doubleEncode']);

$output452 .= '">
												<div class="neos-modal">
													<div class="neos-modal-header">
														<button type="button" class="neos-close neos-button" data-dismiss="modal"></button>
														<div class="neos-header">Do you really want to delete "';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments462 = array();
$arguments462['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments462['keepQuotes'] = false;
$arguments462['encoding'] = 'UTF-8';
$arguments462['doubleEncode'] = true;
$renderChildrenClosure463 = function() use ($renderingContext, $self) {
return NULL;
};
$value464 = ($arguments462['value'] !== NULL ? $arguments462['value'] : $renderChildrenClosure463());

$output452 .= !is_string($value464) && !(is_object($value464) && method_exists($value464, '__toString')) ? $value464 : htmlspecialchars($value464, ($arguments462['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments462['encoding'], $arguments462['doubleEncode']);

$output452 .= '"?</div>
													</div>
													<div class="neos-modal-footer">
														<a href="#" class="neos-button" data-dismiss="modal">Cancel</a>
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments465 = array();
$arguments465['action'] = 'delete';
$arguments465['class'] = 'neos-button neos-button-danger';
// Rendering Array
$array466 = array();
$array466['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments465['arguments'] = $array466;
$arguments465['title'] = 'Delete';
$arguments465['additionalAttributes'] = NULL;
$arguments465['data'] = NULL;
$arguments465['controller'] = NULL;
$arguments465['package'] = NULL;
$arguments465['subpackage'] = NULL;
$arguments465['section'] = '';
$arguments465['format'] = '';
$arguments465['additionalParams'] = array (
);
$arguments465['addQueryString'] = false;
$arguments465['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments465['useParentRequest'] = false;
$arguments465['absolute'] = true;
$arguments465['dir'] = NULL;
$arguments465['id'] = NULL;
$arguments465['lang'] = NULL;
$arguments465['style'] = NULL;
$arguments465['accesskey'] = NULL;
$arguments465['tabindex'] = NULL;
$arguments465['onclick'] = NULL;
$arguments465['name'] = NULL;
$arguments465['rel'] = NULL;
$arguments465['rev'] = NULL;
$arguments465['target'] = NULL;
$renderChildrenClosure467 = function() use ($renderingContext, $self) {
return 'Yes, delete the package';
};
$viewHelper468 = $self->getViewHelper('$viewHelper468', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper468->setArguments($arguments465);
$viewHelper468->setRenderingContext($renderingContext);
$viewHelper468->setRenderChildrenClosure($renderChildrenClosure467);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output452 .= $viewHelper468->initializeArgumentsAndRender();

$output452 .= '
													</div>
												</div>
												<div class="neos-modal-backdrop neos-in"></div>
											</div>
										';
return $output452;
};
$viewHelper469 = $self->getViewHelper('$viewHelper469', $renderingContext, 'TYPO3\Fluid\ViewHelpers\ElseViewHelper');
$viewHelper469->setArguments($arguments450);
$viewHelper469->setRenderingContext($renderingContext);
$viewHelper469->setRenderChildrenClosure($renderChildrenClosure451);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\ElseViewHelper

$output446 .= $viewHelper469->initializeArgumentsAndRender();

$output446 .= '
									';
return $output446;
};
$arguments444['__thenClosure'] = function() use ($renderingContext, $self) {
return '
											<button class="neos-button neos-button-danger neos-disabled" title="This package is protected and cannot be deleted." disabled="disabled"><i class="icon-trash icon-white"></i></button>
										';
};
$arguments444['__elseClosure'] = function() use ($renderingContext, $self) {
$output470 = '';

$output470 .= '
											<button class="neos-button neos-button-danger" title="Click to delete ';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments471 = array();
$arguments471['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments471['keepQuotes'] = false;
$arguments471['encoding'] = 'UTF-8';
$arguments471['doubleEncode'] = true;
$renderChildrenClosure472 = function() use ($renderingContext, $self) {
return NULL;
};
$value473 = ($arguments471['value'] !== NULL ? $arguments471['value'] : $renderChildrenClosure472());

$output470 .= !is_string($value473) && !(is_object($value473) && method_exists($value473, '__toString')) ? $value473 : htmlspecialchars($value473, ($arguments471['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments471['encoding'], $arguments471['doubleEncode']);

$output470 .= '." data-toggle="modal" href="#';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments474 = array();
$arguments474['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.sanitizedPackageKey', $renderingContext);
$arguments474['keepQuotes'] = false;
$arguments474['encoding'] = 'UTF-8';
$arguments474['doubleEncode'] = true;
$renderChildrenClosure475 = function() use ($renderingContext, $self) {
return NULL;
};
$value476 = ($arguments474['value'] !== NULL ? $arguments474['value'] : $renderChildrenClosure475());

$output470 .= !is_string($value476) && !(is_object($value476) && method_exists($value476, '__toString')) ? $value476 : htmlspecialchars($value476, ($arguments474['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments474['encoding'], $arguments474['doubleEncode']);

$output470 .= '"><i class="icon-trash icon-white"></i></button>
											<div class="neos-hide" id="';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments477 = array();
$arguments477['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'package.sanitizedPackageKey', $renderingContext);
$arguments477['keepQuotes'] = false;
$arguments477['encoding'] = 'UTF-8';
$arguments477['doubleEncode'] = true;
$renderChildrenClosure478 = function() use ($renderingContext, $self) {
return NULL;
};
$value479 = ($arguments477['value'] !== NULL ? $arguments477['value'] : $renderChildrenClosure478());

$output470 .= !is_string($value479) && !(is_object($value479) && method_exists($value479, '__toString')) ? $value479 : htmlspecialchars($value479, ($arguments477['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments477['encoding'], $arguments477['doubleEncode']);

$output470 .= '">
												<div class="neos-modal">
													<div class="neos-modal-header">
														<button type="button" class="neos-close neos-button" data-dismiss="modal"></button>
														<div class="neos-header">Do you really want to delete "';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments480 = array();
$arguments480['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments480['keepQuotes'] = false;
$arguments480['encoding'] = 'UTF-8';
$arguments480['doubleEncode'] = true;
$renderChildrenClosure481 = function() use ($renderingContext, $self) {
return NULL;
};
$value482 = ($arguments480['value'] !== NULL ? $arguments480['value'] : $renderChildrenClosure481());

$output470 .= !is_string($value482) && !(is_object($value482) && method_exists($value482, '__toString')) ? $value482 : htmlspecialchars($value482, ($arguments480['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments480['encoding'], $arguments480['doubleEncode']);

$output470 .= '"?</div>
													</div>
													<div class="neos-modal-footer">
														<a href="#" class="neos-button" data-dismiss="modal">Cancel</a>
														';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper
$arguments483 = array();
$arguments483['action'] = 'delete';
$arguments483['class'] = 'neos-button neos-button-danger';
// Rendering Array
$array484 = array();
$array484['packageKey'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'packageKey', $renderingContext);
$arguments483['arguments'] = $array484;
$arguments483['title'] = 'Delete';
$arguments483['additionalAttributes'] = NULL;
$arguments483['data'] = NULL;
$arguments483['controller'] = NULL;
$arguments483['package'] = NULL;
$arguments483['subpackage'] = NULL;
$arguments483['section'] = '';
$arguments483['format'] = '';
$arguments483['additionalParams'] = array (
);
$arguments483['addQueryString'] = false;
$arguments483['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments483['useParentRequest'] = false;
$arguments483['absolute'] = true;
$arguments483['dir'] = NULL;
$arguments483['id'] = NULL;
$arguments483['lang'] = NULL;
$arguments483['style'] = NULL;
$arguments483['accesskey'] = NULL;
$arguments483['tabindex'] = NULL;
$arguments483['onclick'] = NULL;
$arguments483['name'] = NULL;
$arguments483['rel'] = NULL;
$arguments483['rev'] = NULL;
$arguments483['target'] = NULL;
$renderChildrenClosure485 = function() use ($renderingContext, $self) {
return 'Yes, delete the package';
};
$viewHelper486 = $self->getViewHelper('$viewHelper486', $renderingContext, 'TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper');
$viewHelper486->setArguments($arguments483);
$viewHelper486->setRenderingContext($renderingContext);
$viewHelper486->setRenderChildrenClosure($renderChildrenClosure485);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper

$output470 .= $viewHelper486->initializeArgumentsAndRender();

$output470 .= '
													</div>
												</div>
												<div class="neos-modal-backdrop neos-in"></div>
											</div>
										';
return $output470;
};
$viewHelper487 = $self->getViewHelper('$viewHelper487', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper487->setArguments($arguments444);
$viewHelper487->setRenderingContext($renderingContext);
$viewHelper487->setRenderChildrenClosure($renderChildrenClosure445);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output264 .= $viewHelper487->initializeArgumentsAndRender();

$output264 .= '
								</td>
							</tr>
						';
return $output264;
};

$output255 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments262, $renderChildrenClosure263, $renderingContext);

$output255 .= '
					';
return $output255;
};

$output252 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments253, $renderChildrenClosure254, $renderingContext);

$output252 .= '
				</tbody>
			</table>
		</div>
		<div class="neos-footer">
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments488 = array();
// Rendering Boolean node
$arguments488['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'isDevelopmentContext', $renderingContext));
$arguments488['then'] = NULL;
$arguments488['else'] = NULL;
$renderChildrenClosure489 = function() use ($renderingContext, $self) {
return '
				<button type="submit" name="moduleArguments[action]" value="freeze" class="neos-button batch-action neos-disabled" disabled="disabled">
					Freeze <strong>selected</strong> packages
				</button>
				<button type="submit" name="moduleArguments[action]" value="unfreeze" class="neos-button batch-action neos-disabled" disabled="disabled">
					Unfreeze <strong>selected</strong> packages
				</button>
			';
};
$viewHelper490 = $self->getViewHelper('$viewHelper490', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper490->setArguments($arguments488);
$viewHelper490->setRenderingContext($renderingContext);
$viewHelper490->setRenderChildrenClosure($renderChildrenClosure489);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output252 .= $viewHelper490->initializeArgumentsAndRender();

$output252 .= '
			<button class="neos-button neos-button-danger batch-action neos-disabled" data-toggle="modal" href="#delete" disabled="disabled">
				Delete <strong>selected</strong> packages
			</button>
			<button type="submit" name="moduleArguments[action]" value="deactivate" class="neos-button neos-button-warning batch-action neos-disabled" disabled="disabled">
				Deactivate <strong>selected</strong> packages
			</button>
			<button type="submit" name="moduleArguments[action]" value="activate" class="neos-button neos-button-success batch-action neos-disabled" disabled="disabled">
				Activate <strong>selected</strong> packages
			</button>
		</div>
		<div class="neos-hide" id="delete">
			<div class="neos-modal">
				<div class="neos-modal-header">
					<button type="button" class="neos-close" data-dismiss="modal"></button>
					<div class="neos-header">Do you really want to delete the selected packages? This action cannot be undone.</div>
				</div>
				<div class="neos-modal-footer">
					<a href="#" class="neos-button" data-dismiss="modal">Cancel</a>
					<button type="submit" name="moduleArguments[action]" value="delete" class="neos-button neos-button-danger">
						Yes, delete them
					</button>
				</div>
			</div>
			<div class="neos-modal-backdrop neos-in"></div>
		</div>
	';
return $output252;
};
$viewHelper491 = $self->getViewHelper('$viewHelper491', $renderingContext, 'TYPO3\Fluid\ViewHelpers\FormViewHelper');
$viewHelper491->setArguments($arguments250);
$viewHelper491->setRenderingContext($renderingContext);
$viewHelper491->setRenderChildrenClosure($renderChildrenClosure251);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\FormViewHelper

$output249 .= $viewHelper491->initializeArgumentsAndRender();

$output249 .= '

	<script>
		(function($) {
			$(\'#check-all\').change(function() {
				var value = false;
				if ($(this).is(\':checked\')) {
					value = true;
					$(\'.batch-action\').removeClass(\'neos-disabled\').removeAttr(\'disabled\');
				} else {
					$(\'.batch-action\').addClass(\'neos-disabled\').attr(\'disabled\', \'disabled\');
				}
				$(\'tbody input[type="checkbox"]\').prop(\'checked\', value);
			});
			$(\'tbody input[type="checkbox"]\').change(function() {
				if ($(\'tbody input[type="checkbox"]:checked\').length > 0) {
					$(\'.batch-action\').removeClass(\'neos-disabled\').removeAttr(\'disabled\')
				} else {
					$(\'.batch-action\').addClass(\'neos-disabled\').attr(\'disabled\', \'disabled\');
				}
			});
			$(\'.fold-toggle\').click(function() {
				$(this).toggleClass(\'icon-chevron-down icon-chevron-up\');
				$(\'tr.\' + $(this).data(\'toggle\')).toggle();
			});
		})(jQuery);
	</script>
';
return $output249;
};

$output243 .= '';

return $output243;
}


}
#0             168869    