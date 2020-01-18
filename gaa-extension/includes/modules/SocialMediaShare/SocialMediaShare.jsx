// External Dependencies
import React, { Component } from "react";

// Internal Dependencies
import "./style.css";

class SocialMediaShare extends Component {
    static slug = 'gaex_sm_share';

    render() {
        const utils = window.ET_Builder.API.Utils;
        const _ = utils._;
        const l10n  = window.GaaExtensionBuilderData.l10n;
        const platforms = window.GaaExtensionBuilderData.social_share_platforms;
        const platfroms_list = this.props.platfroms_list.split( "|" );
        let output = [];

        if ( _.isArray( platfroms_list ) ) {
            platfroms_list.forEach( ( option, index ) => {
                if (option === 'off') {
                    return;
                }
                let platform = platforms[ index ];

                output.push(
                    <div class="platform" style={ { background: platform.color } }>
                        <a href={ platform.url }>
                        { platform.name }
                        </a>
                    </div>
                );
            });
        }

        if ( output.length > 0 ) {
            return (
                <div>
                    <div class="wp-smshare-wrapper">
                        <div class="share-on">{ l10n.share_on }</div>
                        { output }
                        <div class="wp-smshare-clear"></div>
                    </div>
                </div>
            );
        }
        else {
            return ( null );
        }
    }
}

export default SocialMediaShare;
