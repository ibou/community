import React, {Component} from 'react';
import {MuiThemeProvider} from "material-ui/styles";
import axios from 'axios';

class ItemPost extends Component {
    constructor(props) {
        super(props);
        this.state = {
            post: {},
            isLoading: false,
            error: null
        };
        console.log("hello from ItemPost");
        console.log("==>", this.props.location);

    }

    componentDidMount() {
        this.getPosts('1deb041f-5b61-4ae2-a409-2412a9717fb7');
    }

    getPosts(uuid) {
        this.setState({ isLoading: true });
        axios.get('/posts/data/article/' + uuid)
            .then(result => this.setState({
                post: result.data,
                isLoading: false
            }))
            .catch(error => this.setState({
                error,
                isLoading: false
            }));
    }

    render() {

        const {post, isLoading, error} = this.state;

        if (error) {
            return <p>{error.message}</p>;
        }

        if (Object.keys(post).length === 0) {
            return <p>Loading ...</p>;
        }
        console.log("SSSSSSSSSSSS",post, post.createdAt.toLocaleString());
        return (
            <MuiThemeProvider>
                <div className="cs">
                    <ul>
                        <li>by {post.user.full_name}, </li>
                        <li>le {post.createdAt.toLocaleString()}, </li>
                        <li> {post.uuid} </li>
                        <li> {post.title} </li>
                        <li> {post.content} </li>

                    </ul>
                </div>
            </MuiThemeProvider>
        );

    }
}

export default ItemPost;