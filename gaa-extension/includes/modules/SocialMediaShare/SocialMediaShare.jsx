// External Dependencies
import React, { Component } from "react";

// Internal Dependencies
import "./style.css";

class SocialMediaShare extends Component {
    static slug = 'gaex_sm_share';

    render() {
        const utils = window.ET_Builder.API.Utils;
        const _ = utils._;
        const platforms = window.GaaExtensionBuilderData.social_share_platforms;
        const platfroms_list = this.props.platfroms_list.split("|");
        let output = [];

        if (_.isArray(platfroms_list)) {
            platfroms_list.forEach((option, index) => {
                if (option === 'off') {
                    return;
                }
                let platform = platforms[index];
                let url = platform.base_url + window.location.href;

                output.push(
                    <div class="platform" style={{background: platform.color}}>
                        <a href={url}>
                        {platform.name}
                        </a>
                    </div>
                );
            });
        }

        if (output.length > 0) {
            return (
                <div>
                    <div class="wp-smshare-wrapper">
                        <div class="share-on">Share on: </div>
                        {output}
                        <div class="wp-smshare-clear"></div>
                    </div>
                </div>
            );
        }
        else {
            return (null);
        }
    }
}

export default SocialMediaShare;
