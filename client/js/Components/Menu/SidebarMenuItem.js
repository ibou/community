import React, { Component } from 'react';
import {NavLink, withRouter} from 'react-router-dom';

export class SidebarMenuItem extends Component {

    getNavLinkClass (path) {
        if (this.props.location.pathname === '/') {
            return this.props.location.pathname === path ? 'active' : '';
        }

        return (this.props.location.pathname.includes(path) && path !== '/') ? 'active' : '';
    };

    render() {
        return (
            <li className={this.getNavLinkClass(this.props.link)}>
                <NavLink exact to={this.props.link}>
                    <i className={this.props.icon}/>
                    <span>{this.props.itemName}</span>
                </NavLink>
            </li>
        )
    }
}

export default withRouter(SidebarMenuItem);
