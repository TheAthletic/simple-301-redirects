import React from 'react';
import PropTypes from 'prop-types';
import TopBar from './../components/TopBar';
import ManageLinks from './group/ManageLinks';
import BetterLinks from './../components/BetterLinks';
import Documentation from './../components/Documentation';
const propTypes = {};

const defaultProps = {};

export default function Simple301Redirects(props) {
	return (
		<React.Fragment>
			<TopBar />
			<ManageLinks />
			<BetterLinks />
			<Documentation />
		</React.Fragment>
	);
}

Simple301Redirects.propTypes = propTypes;
Simple301Redirects.defaultProps = defaultProps;
