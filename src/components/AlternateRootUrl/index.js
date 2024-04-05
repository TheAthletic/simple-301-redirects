import React, { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import axios from 'axios';
import PropTypes from 'prop-types';
import UpdateLink from './../updateLink';
import { s3r_nonce } from './../../utils/helper';
const propTypes = {};

const defaultProps = {};

export default function AlternateRootUrl({ initialAlternateRootUrl }) {
	const [alternateRootUrl, setAlternateRootUrl] = useState('');
	useEffect(() => {
		setAlternateRootUrl(initialAlternateRootUrl);
	}, [initialAlternateRootUrl]);

	const buttonHandler = () => {
		if (alternateRootUrl) {
			let form_data = new FormData();
			form_data.append('action', 'simple301redirects/admin/update_link');
			form_data.append('security', s3r_nonce);
			form_data.append('alternateRootUrl', alternateRootUrl);
			return axios.post(ajaxurl, form_data).then(
				(response) => {
					if (response.data) {
						setTimeout(() => {
							setAlternateRootUrl(response.data.data);
						}, 2000);
					}
				},
				(error) => {
					console.log(error);
				},
			);
		}
	};
	const keyPressEventHandler = (event) => {
		if (event.key === 'Enter') {
			buttonHandler();
		}
	};
	return (
		<div className="simple301redirects__alternaterooturl">
			<input
				type="text"
				name="alternaterooturl"
				id="alternaterooturl"
				value={alternateRootUrl}
				onChange={(e) => setAlternateRootUrl(e.target.value)}
				onKeyPress={keyPressEventHandler}
			/>
			<UpdateLink localClickHandler={buttonHandler} />
		</div>
	);
}

AlternateRootUrl.propTypes = propTypes;
AlternateRootUrl.defaultProps = defaultProps;
