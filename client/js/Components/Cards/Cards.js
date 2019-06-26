import React from 'react';
import { MuiThemeProvider } from 'material-ui/styles';
import { renderComponent } from 'recompose';
import ItemCard from './ItemCard';

class Cards extends React.Component {

    constructor() {
        super();
        this.state = {
            entries: []
        };
    }

    componentDidMount() {
        this.getCards();
    }

    getCards() {
        fetch('/data')
            .then(response => response.json())
            .then(entries => {
                this.setState({
                    entries
                });
            })
            .catch(e => {
                this.setState({
                    loading: false,
                    error: {
                        status: true,
                        message: e.toString()
                    }
                })
            })
    }

    render() {

        return (

            <MuiThemeProvider>
                <div style={{ display: 'flex' }}>


                    {this.state.entries.map(
                        (card) => (
                            <ItemCard
                                key={card.id} 
                                card={card}
                                style={{ flex: 1, margin: 10 }}
                            >
                                {card.description}
                            </ItemCard>
                        )
                    )}

                </div>
            </MuiThemeProvider>
            
        );
    }

}

export default Cards;